<?php

namespace App\Http\Controllers;

use App\Models\AdminQueue;
use App\Models\RequestInfo;
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
		if (Auth::user()->is_admin == 1) {
			$my_requests = RequestInfo::where('admin_id', Auth::user()->id)->where('status', 'В работе')->paginate(20);
			$request_id = null;
			if (($request = AdminQueue::where('admin_id', Auth::user()->id)->first()) !== null) {
				$request_id = $request->request_id;
			}
			$recommend_request = RequestInfo::where('id', $request_id)->first();
			return view('admin.my', [
				'my_requests' => $my_requests,
				'recommend_request' => $recommend_request
			]);
		}
		abort(404);
	}

	public function requests()
	{
		if (Auth::user()->is_admin == 1) {
			$all_requests = RequestInfo::orderBy('created_at', 'desc')->paginate(20);
			return view('admin.requests.index', compact('all_requests'));
		}
		abort(404);
	}

	public function show($request_id)
	{
		if (Auth::user()->is_admin == 1) {
			$request_info = RequestInfo::findOrFail($request_id);
			return view('admin.requests.show', compact('request_info'));
		}
		abort(404);
	}
}
