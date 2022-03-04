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
		$data = $this->validateData();
		$data['from_pc'] = false;
		$data['name'] = $request->first_name . '' . $request->last_name;
		$data['ip_address'] = request()->ip();
		$data['date_create'] = Carbon::now();
		$data['status'] = 'В обработке';
		$data['solution_with_me'] = false;
		$data['problem_with_my_pc'] = false;
		$data['status'] = 'В обработке';
		$data['work_time'] = '';
		$data['user_password'] = '';
		if (Auth::check()) {
			$data['user_id'] = Auth::user()->id;
		}
		$request_info = RequestInfo::create($data);
		//$this->storeImage($request_info);
		if (Auth::check()) {
			return redirect('/requests/');
		}
		return redirect('/requests/' . $request_info->id);
	}

	protected function validateData()
	{
		return tap($validateData =  request()->validate([
			'email' => 'required',
			'location' => 'required',
			'phone_call_number' => 'required',
			'topic' => 'required',
			'text' => 'required',
		]), function () {
			if (request()->hasFile('photo')) {
				request()->validate([
					'photo' => 'file|image|max:8000'
				]);
			}
		});
		return $validateData;
	}
}
