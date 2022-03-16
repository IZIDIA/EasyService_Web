<?php

namespace App\Http\Controllers;

use App\Models\RequestInfo;
use Carbon\Carbon;
use Dotenv\Util\Str;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class RequestInfoController extends Controller
{
	public function index()
	{
		if (Auth::check()) {
			$request_infos = RequestInfo::where('user_id', Auth::user()->id)->get()->reverse();
			return view('requests/index', ['request_infos' => $request_infos]);
		} else {
			return view('requests/index');
		}
	}

	public function create()
	{
		$request_info = new RequestInfo();
		return view('requests/create', compact('request_info'));
	}

	public function store(Request $request)
	{
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
			$data['email'] = 'Неизвестно';
			$data['phone_call_number'] = 'Неизвестно';
		}
		if ($request['problem_with_my_pc'] == 'on') {
			$data['problem_with_my_pc'] = true;
			$data['user_password'] = $request->user_password;
		} else {
			$data['problem_with_my_pc'] = false;
		}
		$data['from_pc'] = false;
		$data['ip_address'] = request()->ip();
		$data['date_create'] = Carbon::now();
		$data['status'] = 'В обработке';

		if (Auth::check()) {
			$data['user_id'] = Auth::user()->id;
			$request_info = RequestInfo::create($data);
			$this->storeImage($request_info);
			return redirect('/requests/');
		}
		$data['session_id'] = session()->getId();
		$request_info = RequestInfo::create($data);
		$this->storeImage($request_info);
		return redirect('/requests/' . $request_info->id);
	}

	protected function validateData()
	{
		return tap($validateData =  request()->validate([
			'first_name' => 'required|max:40',
			'last_name' => 'required|max:40',
			'email' => 'required|max:128',
			'location' => 'required|max:255',
			'phone_call_number' => 'required|max:32',
			'inventory_number' => 'max:64',
			'user_password' => 'max:64',
			'topic' => 'required|max:128',
			'text' => 'required|max:4096',
		]), function () {
			if (request()->hasFile('photo')) {
				request()->validate([
					'photo' => 'file|image|max:15000'
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
		]), function () {
			if (request()->hasFile('photo')) {
				request()->validate([
					'photo' => 'file|image|max:15000'
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

	public function show($request_id)
	{
		$request_info = RequestInfo::findOrFail($request_id);
		if ($request_info->session_id == session()->getId() || (Auth::check() &&  Auth::user()->id == $request_info->user_id)) {
			return view('requests.show', compact('request_info'));
		}
		abort(404);
	}
}
