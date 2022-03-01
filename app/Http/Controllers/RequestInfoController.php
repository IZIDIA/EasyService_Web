<?php

namespace App\Http\Controllers;

use App\Models\RequestInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestInfoController extends Controller
{
	public function index()
	{
		if (Auth::check()) {
			$request_infos = RequestInfo::where('user_id', Auth::user()->id)->get()->reverse();
			return view('requests/index', ['request_infos' => $request_infos]);
		}
		else{
			return view('requests/index');
		}
	}

	public function create()
	{
		$request_info = new RequestInfo();
		return view('requests/create', compact('request_info'));
	}
}
