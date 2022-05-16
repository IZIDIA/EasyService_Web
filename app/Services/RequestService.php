<?php

use App\Jobs\RequestServiceJob;
use App\Models\Admin;
use App\Models\AdminQueue;
use App\Models\Criterion;
use App\Models\Option;
use App\Models\PcInfo;
use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Bus\Dispatcher;

class RequestService
{
	//При всех действиях с заявками
	public static function check_free(Admin $admin)
	{
		$requests = RequestInfo::where('status', 'В работе')
			->where('admin_id', $admin->user_id)
			->get();
		if ($requests->isEmpty()) {
			$admin->free = true;
		} else {
			$admin->free = false;
		}
		$admin->save();
	}
	//При включении опции
	public static function enqueue(Admin $admin)
	{
		$record = AdminQueue::where('admin_id', $admin->user_id);
		if (is_null($record->first())) {
			AdminQueue::create(['admin_id' => $admin->user_id]);
		}
	}
	//При отключении опции
	//Если распределённая заявки не меняет свой статус (updated) в течении (настройки->часов)
	public static function dequeue(Admin $admin)
	{
		$record = AdminQueue::where('admin_id', $admin->user_id);
		if (!is_null($record->first())) {
			$job = DB::table('jobs')->where('id', $record->first()->job_id);
			if ($job->exists()) {
				$job->delete();
			}
			$record->delete();
		}
	}
	//При принятии любой заявки
	//При отмене любой заявки
	//При удалении заявки
	public static function clear_request_id(RequestInfo $request)
	{
		if (!is_null($adminQueue = AdminQueue::where('request_id', $request->id)->first())) {
			$adminQueue->request_id = NULL;
			$adminQueue->save();
			$job = DB::table('jobs')->where('id', $adminQueue->job_id);
			if ($job->exists()) {
				$job->delete();
			}
		}
	}
	//При включении опции
	//При создании заявки пользователем
	//При отмене, отказе, завершении, восстановлении , удалении заявки администратором
	public static function distribute()
	{
		$requests_id_queue = DB::table('request_infos')
			->leftJoin('admin_queues', 'request_infos.id', '=', 'admin_queues.request_id')
			->select('request_infos.id')
			->whereNull('admin_queues.id')
			->where('status', 'В обработке')
			->orderBy('request_infos.created_at')
			->get();
		if ($requests_id_queue->isEmpty()) {
			return;
		}
		$admins_id_queue = DB::table('admins')
			->join('admin_queues', 'admins.user_id', '=', 'admin_queues.admin_id')
			->select('admins.user_id')
			->where('get_recommendation', true)
			->where('free', true)
			->whereNull('admin_queues.request_id')
			->orderBy('week_time')
			->get();
		foreach ($requests_id_queue as $request_id) {
			if ($admins_id_queue->isEmpty()) {
				return;
			}
			$queue = AdminQueue::where('admin_id', $admins_id_queue->first()->user_id)->first();
			$queue->request_id = $request_id->id;
			$queue->distributed_lifetime =  Option::find(1)->value('time_to_accept_distributed');
			$job = (new RequestServiceJob($admins_id_queue->first()->user_id))->onQueue('q2')->delay(now()->addHours($queue->distributed_lifetime));
			$queue->job_id = app(Dispatcher::class)->dispatch($job);
			$queue->save();
			$admins_id_queue->forget($admins_id_queue->keys()->first());
		}
	}
	//Проверка на соответствие критериев
	public static function check_criterion($request_id)
	{
		$warning_message = [];
		$pc_info = PcInfo::where('request_info_id', $request_id)->first();
		$criterions = Criterion::first();
		$specs = json_decode($pc_info->specs, true);
		$disks = json_decode($pc_info->disks, true);
		$network = json_decode($pc_info->network, true);
		$temps = json_decode($pc_info->temps, true);
		$performance = json_decode($pc_info->performance, true);
		$active_processes = json_decode($pc_info->active_processes, true);
		$installed_programs = json_decode($pc_info->installed_programs, true);
		$autoload_programs = json_decode($pc_info->autoload_programs, true);
		$required_active_processes = json_decode($criterions->required_active_processes, true);
		$required_installed_programs = json_decode($criterions->required_installed_programs, true);
		$required_autoload_programs = json_decode($criterions->required_autoload_programs, true);
		if ($criterions->ethernet && $network['PingGoogle'] === false && $network['PingYandex'] === false) {
			$warning_message[] = 'Отсутствует доступ в интернет';
		}
		if ($criterions->gpu_install && empty($temps['GPUTemp']) && empty($performance['GPULoad'])) {
			$warning_message[] = 'Отсутствует дискретная видеокарта';
		}

		if ($criterions->disk_status && !empty($disks['Disk'])) {
			foreach ($disks['Disk'] as $item) {
				if ($item['MediaStatus'] !== 'OK') {
					$warning_message[] = $item['VolumeName'] . ' (' . $item['DriveName'] . ') имеет статус: ' .  $item['MediaStatus'];
				}
			}
		}

		if ($criterions->check_max_temp_cpu && !empty($temps['CPUTemp'])) {
			foreach ($temps['CPUTemp'] as $item) {
				if ($item['Value'] >= $criterions->max_temp_cpu) {
					$warning_message[] = 'Температура ' . $item['Key'] . ' превышает ' . $criterions->max_temp_cpu . ' °C';
				}
			}
		}
		if ($criterions->check_max_temp_gpu && !empty($temps['GPUTemp'])) {
			foreach ($temps['GPUTemp'] as $item) {
				if ($item['Value'] >= $criterions->max_temp_gpu) {
					$warning_message[] = 'Температура ' . $item['Key'] . ' превышает ' . $criterions->max_temp_gpu . ' °C';
				}
			}
		}
		if ($criterions->check_max_load_cpu && !empty($performance['CPULoad'])) {
			foreach ($performance['CPULoad'] as $item) {
				if ($item['Value'] >= $criterions->max_load_cpu) {
					$warning_message[] = 'Использование ' . $item['Key'] . ' превышает ' . $criterions->max_load_cpu . ' %';
				}
			}
		}
		if ($criterions->check_max_load_gpu && !empty($performance['GPULoad'])) {
			foreach ($performance['GPULoad'] as $item) {
				if ($item['Value'] >= $criterions->max_load_gpu) {
					$warning_message[] = 'Использование ' . $item['Key'] . ' превышает ' . $criterions->max_load_gpu . ' %';
				}
			}
		}
		if ($criterions->check_max_load_ram && !empty($performance['RAMLoad'])) {
			foreach ($performance['RAMLoad'] as $item) {
				if ($item['Value'] >= $criterions->max_load_ram) {
					$warning_message[] = 'Использование ОЗУ превышает ' . $criterions->max_load_ram . ' %';
				}
			}
		}
		if ($criterions->check_min_cores_count && !empty($specs['CPU'])) {
			foreach ($specs['CPU'] as $item) {
				if ($item['CPULogicalCores'] < $criterions->min_cores_count) {
					$warning_message[] = 'Количество логических ядер ' . $item['CPUName'] . ' менее ' . $criterions->min_cores_count;
				}
			}
		}
		if ($criterions->check_min_ram_size && !empty($specs['RAM'])) {
			$ram_size = 0;
			foreach ($specs['RAM'] as $item) {
				$ram_size += $item['MemorySize'];
			}
			if ($ram_size < $criterions->min_ram_size) {
				$warning_message[] = 'Объём ОЗУ менее ' . $criterions->min_ram_size . ' ГБ';
			}
		}
		if ($criterions->check_active_processes && !empty($required_active_processes)) {
			$missing_active_processes = [];
			$programs_string = strtolower(implode(' ', $active_processes['ActiveProcessesList']));
			foreach ($required_active_processes as $item) {
				if (!str_contains($programs_string, strtolower($item))) {
					$missing_active_processes[] = $item;
				}
			}
			if (!empty($missing_active_processes)) {
				$warning_message[] = 'Отсутствуют активные процессы: ' . implode(', ', array_map(function ($item) {
					return $item;
				}, $missing_active_processes));
			}
		}
		if ($criterions->check_installed_programs && !empty($required_installed_programs)) {
			$missing_installed_programs = [];
			$programs_string = strtolower(implode(' ', $installed_programs['InstalledProgramsList']));
			foreach ($required_installed_programs as $item) {
				if (!str_contains($programs_string, strtolower($item))) {
					$missing_installed_programs[] = $item;
				}
			}
			if (!empty($missing_installed_programs)) {
				$warning_message[] = 'Отсутствуют установленные программы: ' . implode(', ', array_map(function ($item) {
					return $item;
				}, $missing_installed_programs));
			}
		}
		if ($criterions->check_autoload_programs && !empty($required_autoload_programs)) {
			$missing_autoload_programs = [];
			$programs_string = strtolower(implode(' ', $autoload_programs['AutoloadProgramsList']));
			foreach ($required_autoload_programs as $item) {
				if (!str_contains($programs_string, strtolower($item))) {
					$missing_autoload_programs[] = $item;
				}
			}
			if (!empty($missing_autoload_programs)) {
				$warning_message[] = 'Отсутствуют программы в автозапуске: ' . implode(', ', array_map(function ($item) {
					return $item;
				}, $missing_autoload_programs));
			}
		}
		return $warning_message;
	}
}
