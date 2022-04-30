<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\PcInfo;
use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RequestService;

class ApiRequestInfoController extends Controller
{
	private static  $checker = 'accdede43f326c52d88d62b98de5e940'; //md-5 юпитер

	public function index($mac, Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			$json =  RequestInfo::select('id', 'status', 'created_at')
				->where('mac', $mac)
				->where('created_at', '>=', DB::raw('DATE_SUB(CURRENT_DATE, INTERVAL 90 DAY)'))
				->orderBy('created_at', 'desc')->get();
			foreach ($json as $item) {
				// if (!is_null($item->user_admin)) {
				// 	$item['admin'] = $item->user_admin->name;
				// }
				$item['beauty_created_at'] = $item->created_at->format('d.m.y H:i');
				// if (!is_null($item->closed_at)) {
				// 	$item['beauty_closed_at'] = $item->closed_at->format('d.m.y H:i');
				// }
			}
			return $json;
		}
		return abort(404);
	}

	public function info(Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			$json = Option::select('company_name', 'welcome_text_app')->first();
			return $json;
		}
		return abort(404);
	}

	public function store(Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			if ($request['anonym'] == 0) {
				$data = $this->validateData();
				$data['name'] = $request->first_name . ' ' . $request->last_name;
				if ($request['solution_with_me'] == 1) {
					$data['solution_with_me'] = NULL;
				} else {
					if ($request['solution_with_me'] == 2) {
						$data['solution_with_me'] = true;
					} else {
						$data['solution_with_me'] = false;
					}
					$data['work_time'] = '';
					if ($request['monday'] == 'on') {
						$data['work_time'] .= 'ПН ' . 'С: ' . $request['from_monday'] . ' До: ' . $request['to_monday'] . PHP_EOL;
					}
					if ($request['tuesday'] == 'on') {
						$data['work_time'] .= 'ВТ ' . 'С: ' . $request['from_tuesday'] . ' До: ' . $request['to_tuesday'] . PHP_EOL;
					}
					if ($request['wednesday'] == 'on') {
						$data['work_time'] .= 'СР ' . 'С: ' . $request['from_wednesday'] . ' До: ' . $request['to_wednesday'] . PHP_EOL;
					}
					if ($request['thursday'] == 'on') {
						$data['work_time'] .= 'ЧТ ' . 'С: ' . $request['from_thursday'] . ' До: ' . $request['to_thursday'] . PHP_EOL;
					}
					if ($request['friday'] == 'on') {
						$data['work_time'] .= 'ПТ ' . 'С: ' . $request['from_friday'] . ' До: ' . $request['to_friday'] . PHP_EOL;
					}
					if ($request['saturday'] == 'on') {
						$data['work_time'] .= 'СБ ' . 'С: ' . $request['from_saturday'] . ' До: ' . $request['to_saturday'] . PHP_EOL;
					}
					if ($request['sunday'] == 'on') {
						$data['work_time'] .= 'ВС ' . 'С: ' . $request['from_sunday'] . ' До: ' . $request['to_sunday'] . PHP_EOL;
					}
				}
			} else {
				$data = $this->anonymValidateData();
				$data['name'] = 'Аноним';
				$data['email'] = NULL;
				$data['phone_call_number'] = NULL;
			}
			if ($request['problem_with_my_pc'] == 'on') {
				$data['problem_with_my_pc'] = true;
				$data['user_password'] = $request->user_password;
			} else {
				$data['problem_with_my_pc'] = false;
			}
			$data['from_pc'] = true;
			$data['status'] = 'В обработке';
			$json_comment = json_encode(array(
				0 =>
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Заявка создана',
				),
			));
			$data['comments'] = $json_comment;
			$request_info = RequestInfo::create($data);
			$this->storeImage($request_info);
			if ($request['problem_with_my_pc'] == 'on') {
				$this->storePcInfo($request_info);
			}
			RequestService::distribute();
			return response(['Message' => 'Successfully']);
		}
		return abort(404);
	}

	protected function validateData()
	{
		return tap($validateData =  request()->validate([
			'solution_with_me' => 'required',
			'first_name' => 'required|max:40',
			'last_name' => 'required|max:40',
			'email' => 'required|max:128',
			'location' => 'required|max:255',
			'phone_call_number' => 'required|max:32',
			'inventory_number' => 'max:64',
			'user_password' => 'max:64',
			'topic' => 'required|max:128',
			'text' => 'required|max:4096',
			'ip_address' => 'required',
			'mac' => 'required',
		]), function () {
			if (request()->hasFile('photo')) {
				request()->validate([
					'photo' => 'file|image|max:11000'
				]);
			}
		});
		return $validateData;
	}

	protected function anonymValidateData()
	{
		return tap($validateData =  request()->validate([
			'location' => 'required|max:255',
			'inventory_number' => 'max:64',
			'topic' => 'required|max:128',
			'text' => 'required|max:4096',
			'ip_address' => 'required',
			'mac' => 'required',
		]), function () {
			if (request()->hasFile('photo')) {
				request()->validate([
					'photo' => 'file|image|max:11000'
				]);
			}
		});
		return $validateData;
	}

	private function storeImage($request_info)
	{
		if (request()->has('photo')) {
			$request_info->update([
				'photo' => request()->photo->store('img', 'public'),
			]);
		}
	}
	private function storePcInfo($request_info)
	{
		$pcInfo = $this->validatePcInfoData();
		$pcInfo['request_info_id'] = $request_info->id;
		PcInfo::create($pcInfo);
	}

	protected function validatePcInfoData()
	{
		return tap($validateData =  request()->validate([
			'operating_system' => 'required|max:100000',
			'specs' => 'required|max:100000',
			'temps' => 'required|max:100000',
			'active_processes' => 'required|max:100000',
			'network' => 'required|max:100000',
			'devices' => 'required|max:100000',
			'disks' => 'required|max:100000',
			'installed_programs' => 'required|max:100000',
			'autoload_programs' => 'required|max:100000',
			'performance' => 'required|max:100000',
		]), function () {
		});
		return $validateData;
	}
}
