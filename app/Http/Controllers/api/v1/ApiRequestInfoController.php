<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
			$json =  RequestInfo::select('id', 'status', 'topic')
				->where('mac', $mac)
				->where('created_at', '>=', DB::raw('DATE_SUB(CURRENT_DATE, INTERVAL 90 DAY)'))
				->orderBy('created_at', 'desc')
				->get();
			return $json;
		}
		return abort(404);
	}

	public function show($mac, $id, Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			$json =  RequestInfo::where('mac', $mac)->findOrFail($id);
			if (!is_null($json->user_admin)) {
				$json['admin'] = $json->user_admin->name;
			}
			$json['beauty_created_at'] = $json->created_at->format('d.m.y H:i');
			if (!is_null($json->closed_at)) {
				$json['beauty_closed_at'] = $json->closed_at->format('d.m.y H:i');
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
					$work_time = array();
					if ($request['monday'] == 'on') {
						$work_time += ['monday' =>
						array(
							'from' => $request['from_monday'],
							'to' => $request['to_monday'],
						)];
					}
					if ($request['tuesday'] == 'on') {
						$work_time += ['tuesday' =>
						array(
							'from' => $request['from_tuesday'],
							'to' => $request['to_tuesday'],
						)];
					}
					if ($request['wednesday'] == 'on') {
						$work_time += ['wednesday' =>
						array(
							'from' => $request['from_wednesday'],
							'to' => $request['to_wednesday'],
						)];
					}
					if ($request['thursday'] == 'on') {
						$work_time += ['thursday' =>
						array(
							'from' => $request['from_thursday'],
							'to' => $request['to_thursday'],
						)];
					}
					if ($request['friday'] == 'on') {
						$work_time += ['friday' =>
						array(
							'from' => $request['from_friday'],
							'to' => $request['to_friday'],
						)];
					}
					if ($request['saturday'] == 'on') {
						$work_time += ['saturday' =>
						array(
							'from' => $request['from_saturday'],
							'to' => $request['to_saturday'],
						)];
					}
					if ($request['sunday'] == 'on') {
						$work_time += ['sunday' =>
						array(
							'from' => $request['from_sunday'],
							'to' => $request['to_sunday'],
						)];
					}
					if (!empty($work_time)) {
						$data['work_time'] = json_encode($work_time);
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

	public function cancel($mac, $id, Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			$request_info =  RequestInfo::where('mac', $mac)->findOrFail($id);
			$request_info->status = 'Отменено';
			$json_comment = json_decode($request_info->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Заявка отменена, заявителем ' . '"' . $request_info->name . '"',
				);
			$request_info->comments = json_encode($json_comment);
			$request_info->save();
			if (!is_null($request_info->admin_id)) {
				RequestService::check_free(Admin::where('user_id', 	$request_info->admin_id)->first());
			}
			if (Option::find(1)->distributed_requests) {
				RequestService::clear_request_id($request_info);
				RequestService::distribute();
			}
			return response(['Message' => 'Successfully']);
		}
		return abort(404);
	}

	public function comment($mac, $id, Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			$request_info =  RequestInfo::where('mac', $mac)->findOrFail($id);
			$data = request()->validate([
				'comment_text' => 'required|min:1|max:512',
			]);
			$json_comment = json_decode($request_info->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => $request_info->name,
					'Message' => str_replace(PHP_EOL, ' ', $data['comment_text']),
				);
			$request_info->comments = json_encode($json_comment);
			$request_info->save();
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
