<?php

namespace App\Http\Controllers;

use App\Models\AdminQueue;
use App\Models\Option;
use App\Models\PcInfo;
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
			$distributed_request_id = null;
			if (($request = AdminQueue::where('admin_id', Auth::user()->id)->first()) !== null) {
				$distributed_request_id = $request->request_id;
			}
			$distributed_request = RequestInfo::where('id', $distributed_request_id)->first();
			return view('admin.my', [
				'my_requests' => $my_requests,
				'distributed_request' => $distributed_request
			]);
		}
		abort(404);
	}

	public function requests()
	{
		if (Auth::user()->is_admin == 1) {
			$requests = RequestInfo::orderBy('created_at', 'desc')->paginate(20);
			return view('admin.requests.index', compact('requests'));
		}
		abort(404);
	}

	public function show($request_id)
	{
		if (Auth::user()->is_admin == 1) {
			$request_info = RequestInfo::findOrFail($request_id);
			$distributed_request = AdminQueue::where('request_id', $request_id)->first();
			return view('admin.requests.show', [
				'request_info' => $request_info,
				'comments' => json_decode($request_info->comments, true),
				'pc_info' => PcInfo::where('request_info_id', $request_id)->first(),
				'distributed_request' => $distributed_request,
			]);
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
		if (Auth::user()->is_admin == true) {
			if ($request->status == 'В обработке' || ($request->status == 'В работе' &&  $request->admin_id == Auth::user()->id)) {
				$request->status = 'Отменено';
				$request->time_remaining = NULL;
				$request->closed_at = Carbon::now();
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
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function accept(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true) {
			if ($request->status == 'В обработке') {
				$request->status = 'В работе';
				$request->time_remaining = Option::find(1)->value('time_to_work');
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
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function deny(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true) {
			if ($request->admin_id == Auth::user()->id && $request->status == 'В работе') {
				$request->status = 'В обработке';
				$request->time_remaining = NULL;
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
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function complete(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true) {
			if ($request->admin_id == Auth::user()->id && $request->status == 'В работе') {
				$request->status = 'Завершено';
				$request->time_remaining = NULL;
				$request->closed_at = Carbon::now();
				$json_comment = json_decode($request->comments, true);
				$json_comment[] =
					array(
						'Time' => Carbon::now()->format('d.m.y H:i'),
						'Name' => 'Система',
						'Message' => 'Администратор ' . '"' . Auth::user()->name . '" завершил выполнение заявки',
					);
				$request->comments = json_encode($json_comment);
				$request->save();
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function restore(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true) {
			if ($request->status == 'Отменено' || $request->status == 'Завершено') {
				$request->status = 'В обработке';
				$request->time_remaining = NULL;
				$request->closed_at = NULL;
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
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function time(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true) {
			if ($request->admin_id == Auth::user()->id && $request->status == 'В работе') {
				$request->time_remaining += 24;
				$request->save();
				return redirect('/admin/requests/' . $request->id)->with('autofocus', true);;
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function destroy(RequestInfo $request)
	{
		if (Auth::user()->is_admin == true) {
			if ($request->status != 'В работе' || $request->admin_id == Auth::user()->id) {
				//ДОБАВИТЬ
				//Если есть зависимость (подробная информация) -> удаляем зависимую запись
				$request->delete();
				return redirect('/admin/requests');
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}
}
