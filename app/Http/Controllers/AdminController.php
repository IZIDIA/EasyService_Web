<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminQueue;
use App\Models\Option;
use App\Models\PcInfo;
use App\Models\RequestInfo;
use App\Models\User;
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
		if (Auth::user()->is_admin) {
			$distributed_request_id = null;
			if (($request = AdminQueue::where('admin_id', Auth::user()->id)->first()) !== null) {
				$distributed_request_id = $request->request_id;
			}
			return view('admin.my', [
				'my_requests' => RequestInfo::where('admin_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(20),
				'distributed_request' => RequestInfo::where('id', $distributed_request_id)->first(),
				'type' => 'Все',
			]);
		}
		abort(404);
	}

	public function my_completed()
	{
		if (Auth::user()->is_admin) {
			$distributed_request_id = null;
			if (($request = AdminQueue::where('admin_id', Auth::user()->id)->first()) !== null) {
				$distributed_request_id = $request->request_id;
			}
			return view('admin.my', [
				'my_requests' => RequestInfo::where('admin_id', Auth::user()->id)->where('status', 'Завершено')->orderBy('created_at', 'desc')->paginate(20),
				'distributed_request' => RequestInfo::where('id', $distributed_request_id)->first(),
				'type' => 'Завершённые',
			]);
		}
		abort(404);
	}

	public function my_in_work()
	{
		if (Auth::user()->is_admin) {
			$distributed_request_id = null;
			if (($request = AdminQueue::where('admin_id', Auth::user()->id)->first()) !== null) {
				$distributed_request_id = $request->request_id;
			}
			return view('admin.my', [
				'my_requests' => RequestInfo::where('admin_id', Auth::user()->id)->where('status', 'В работе')->orderBy('created_at', 'desc')->paginate(20),
				'distributed_request' => RequestInfo::where('id', $distributed_request_id)->first(),
				'type' => 'В работе',
			]);
		}
		abort(404);
	}


	public function requests()
	{
		if (Auth::user()->is_admin) {
			$requests = RequestInfo::orderBy('created_at', 'desc')->paginate(20);
			return view('admin.requests.index', [
				'requests' => $requests,
				'type' => 'Все',
			]);
		}
		abort(404);
	}

	public function requests_completed()
	{
		if (Auth::user()->is_admin) {
			$requests = RequestInfo::where('status', 'Завершено')->orderBy('created_at', 'desc')->paginate(20);
			return view('admin.requests.index', [
				'requests' => $requests,
				'type' => 'Завершённые',
			]);
		}
		abort(404);
	}

	public function requests_in_work()
	{
		if (Auth::user()->is_admin) {
			$requests = RequestInfo::where('status', 'В работе')->orderBy('created_at', 'desc')->paginate(20);
			return view('admin.requests.index', [
				'requests' => $requests,
				'type' => 'В работе',
			]);
		}
		abort(404);
	}

	public function requests_in_processing()
	{
		if (Auth::user()->is_admin) {
			$requests = RequestInfo::where('status', 'В обработке')->orderBy('created_at', 'desc')->paginate(20);
			return view('admin.requests.index', [
				'requests' => $requests,
				'type' => 'В обработке',
			]);
		}
		abort(404);
	}

	public function requests_canceled()
	{
		if (Auth::user()->is_admin) {
			$requests = RequestInfo::where('status', 'Отменено')->orderBy('created_at', 'desc')->paginate(20);
			return view('admin.requests.index', [
				'requests' => $requests,
				'type' => 'Отменённые',
			]);
		}
		abort(404);
	}


	public function request_show($request_id)
	{
		if (Auth::user()->is_admin) {
			$request_info = RequestInfo::findOrFail($request_id);
			return view('admin.requests.show', [
				'request_info' => $request_info,
				'comments' => json_decode($request_info->comments, true),
				'pc_info' => PcInfo::where('request_info_id', $request_id)->first(),
				'distributed_request' => AdminQueue::where('request_id', $request_id)->first(),
			]);
		}
		abort(404);
	}

	public function users()
	{
		if (Auth::user()->is_admin) {
			$users = User::paginate(50);
			return view('admin.users.index', compact('users'));
		}
		abort(404);
	}

	public function user_show($user_id)
	{
		if (Auth::user()->is_admin) {
			$user = User::findOrFail($user_id);
			if ($user->is_admin) {
				return view('admin.users.show', [
					'user' => $user,
					'created' => count(RequestInfo::where('user_id', $user_id)->get()),
					'done' => count(RequestInfo::where('admin_id', $user_id)->where('status', 'Завершено')->get()),
				]);
			}
			return view('admin.users.show', [
				'user' => $user,
				'created' => count(RequestInfo::where('user_id', $user_id)->get()),
			]);
		}
		abort(404);
	}

	public function change_status($user_id, $status)
	{
		if (Auth::user()->admin->is_master) {
			$user = User::findOrFail($user_id);
			switch ($status) {
				case 'admin':
					if (!$user->is_admin) {
						$user->is_admin = true;
						$user->save();
						Admin::create([
							'user_id' => $user_id,
						]);
					}
					break;
				case 'master':
					if ($user->is_admin && !$user->admin->is_master) {
						$admin = $user->admin;
						$admin->is_master = true;
						$admin->save();
					}
					break;
				case 'downgrade':
					if ($user->admin->is_master) {
						$admin = $user->admin;
						$admin->is_master = false;
						$admin->save();
					} else {
						if ($user->is_admin) {
							$user->is_admin = false;
							$user->save();
							$admin = $user->admin;
							$admin->delete();
						}
					}
					break;
				default:
					abort(404);
					break;
			}
			return redirect('/admin/users/' . $user_id);
		}
		abort(404);
	}

	public function user_destroy(User $user)
	{
		if (Auth::user()->is_admin && Auth::user()->admin->is_master) {
			if ($user->is_admin) {
				$admin = $user->admin;
				$admin->delete();
			}
			$user->delete();
			return redirect('/admin/users');
		}
		abort(404);
	}

	public function comment(RequestInfo $request)
	{
		if (Auth::user()->is_admin) {
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
		if (Auth::user()->is_admin) {
			if ($request->status == 'В обработке' || ($request->status == 'В работе' &&  ($request->admin_id == Auth::user()->id || Auth::user()->admin->is_master))) {
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
		if (Auth::user()->is_admin) {
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
		if (Auth::user()->is_admin) {
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
		if (Auth::user()->is_admin) {
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
		if (Auth::user()->is_admin) {
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
		if (Auth::user()->is_admin) {
			if ($request->admin_id == Auth::user()->id && $request->status == 'В работе') {
				$request->time_remaining += 24;
				$request->save();
				return redirect('/admin/requests/' . $request->id)->with('autofocus', true);;
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function request_destroy(RequestInfo $request)
	{
		if (Auth::user()->is_admin && Auth::user()->admin->is_master) {
			if ($request->status != 'В работе' || $request->admin_id == Auth::user()->id || Auth::user()->admin->is_master) {
				//ДОБАВИТЬ
				//Если есть зависимость (подробная информация) -> удаляем зависимую запись
				$request->delete();
				return redirect('/admin/requests');
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function options()
	{
		if (Auth::user()->is_admin) {
			$admin = Auth::user()->admin;
			$options = Option::first();
			return view('admin.options', [
				'admin' => $admin,
				'options' => $options,
			]);
		}
		abort(404);
	}

	public function recommendation(Request $request)
	{
		if (Auth::user()->is_admin) {
			$admin = Auth::user()->admin;
			$admin->get_recommendation = $request->status;
			$admin->save();
			return response()->json(['success' => 'Recommendation changed successfully.']);
		}
		abort(404);
	}
}
