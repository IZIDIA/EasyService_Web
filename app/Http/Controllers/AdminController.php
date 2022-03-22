<?php

namespace App\Http\Controllers;

use App\Models\AdminQueue;
use App\Models\RequestInfo;
use Carbon\Carbon;
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
			return view('admin.requests.show', compact('request_info'))->with('comments', json_decode($request_info->comments, true));
		}
		abort(404);
	}

	public function comment(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true) {
			$data = request()->validate([
				'comment_text' => 'required|min:1|max:512',
			]);
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => Auth::user()->name,
					'Message' => str_replace(PHP_EOL, ' ', $data['comment_text']),
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function cancel(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true && !($request->status == 'Отменено' || $request->status == 'Завершено')) {
			$request->status = 'Отменено';
			$request->admin_id = NULL;
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Заявка отменена, администратором ' . '"' . Auth::user()->name . '"',
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function accept(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true && $request->status == 'В обработке') {
			$request->status = 'В работе';
			$request->admin_id = Auth::user()->id;
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Заявка принята в работу, администратором ' . '"' . Auth::user()->name . '"',
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function deny(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true && $request->status == 'В работе') {
			$request->status = 'В обработке';
			$request->admin_id = NULL;
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Администратор ' . '"' . Auth::user()->name . '" отказался от заявки',
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function complete(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true && $request->status == 'В работе') {
			$request->status = 'Завершено';
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Администратор ' . '"' . Auth::user()->name . '" завершил выполнение заявки',
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function restore(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true && ($request->status == 'Отменено' || $request->status == 'Завершено')) {
			$request->status = 'В обработке';
			$request->admin_id = NULL;
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Заявка восстановлена, администратором ' . '"' . Auth::user()->name . '"',
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}
}
