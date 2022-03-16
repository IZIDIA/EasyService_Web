<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

	public function index()
	{
		return redirect('admin/my');
	}

	public function my()
	{
		//$request_info = RequestInfo::findOrFail($request_id);
		if (Auth::check() &&  Auth::user()->is_admin == 1) {
			//	return view('admin/my', ['request_infos' => $request_infos]);
			return view('admin/my');
		}
		abort(404);
	}
}
