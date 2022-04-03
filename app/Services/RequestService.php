<?php

use App\Jobs\RequestServiceJob;
use App\Models\Admin;
use App\Models\AdminQueue;
use App\Models\Option;
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
		AdminQueue::create(['admin_id' => $admin->user_id]);
	}
	//При отключении опции
	//Если распределённая заявки не меняет свой статус (updated) в течении (настройки->часов)
	public static function dequeue(Admin $admin)
	{
		$record = AdminQueue::where('admin_id', $admin->user_id);
		if (!is_null($record->first())) {
			$record->delete();
		}
	}
	//При принятии любой заявки
	//При отмене любой заявки
	//При удалении ->nullOnDelete();
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
		foreach ($requests_id_queue as $request_id) {
			$admins_id_queue = DB::table('admins')
				->join('admin_queues', 'admins.user_id', '=', 'admin_queues.admin_id')
				->select('admins.user_id')
				->where('get_recommendation', true)
				->where('free', true)
				->whereNull('admin_queues.request_id')
				->orderBy('week_time')
				->get();
			if ($admins_id_queue->isEmpty()) {
				return;
			}
			$queue = AdminQueue::where('admin_id', $admins_id_queue->first()->user_id)->first();
			$queue->request_id = $request_id->id;
			$queue->distributed_lifetime =  Option::find(1)->value('time_to_accept_distributed');
			$job = (new RequestServiceJob($admins_id_queue->first()->user_id))->onQueue('distributed_requests')->delay(now()->addHours($queue->distributed_lifetime));
			$queue->job_id = app(Dispatcher::class)->dispatch($job);
			$queue->save();
		}
	}
}
