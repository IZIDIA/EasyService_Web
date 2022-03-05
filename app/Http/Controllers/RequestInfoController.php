<?php

namespace App\Http\Controllers;

use App\Models\RequestInfo;
use Carbon\Carbon;
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
		//Валидация данных, которые нельзя сразу добавить в массив
		// request()->validate([
		// 	'first_name' => 'required|max:40',
		// 	'last_name' => 'required|max:40'
		// ]);
		//Валидация данных, которые пойдут в итоговый массив
		$data = $this->validateData();
		$data['from_pc'] = false;
		$data['name'] = $request->first_name . ' ' . $request->last_name;
		$data['ip_address'] = request()->ip();
		$data['date_create'] = Carbon::now();
		$data['status'] = 'В обработке';
		if ($request['solution_with_me'] == 1) {
			$data['solution_with_me'] = NULL;
		} else if ($request['solution_with_me'] == 2) {
			$data['solution_with_me'] = true;
		} else {
			$data['solution_with_me'] = false;
		}
		$data['work_time'] = '';
		if ($request['problem_with_my_pc'] == 'on') {
			$data['problem_with_my_pc'] = true;
			$data['user_password'] = $request->user_password;
		} else {
			$data['problem_with_my_pc'] = false;
		}
		$data['status'] = 'В обработке';

		if (Auth::check()) {
			$data['user_id'] = Auth::user()->id;
			$request_info = RequestInfo::create($data);
			$this->storeImage($request_info);
			return redirect('/requests/');
		}
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
}
