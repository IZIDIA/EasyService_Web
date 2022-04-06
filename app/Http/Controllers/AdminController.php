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
use Illuminate\Support\Facades\DB;
use RequestService;
use TimerService;

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
			$requests = RequestInfo::orderBy('created_at', 'desc')->paginate(30);
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
				'user' => Auth::user(),
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

	public function user_destroy(User $user)
	{
		if (Auth::user()->is_admin && Auth::user()->admin->is_master) {
			$requests = RequestInfo::where('admin_id', $user->id)->get();
			foreach ($requests as $request) {
				$request->status = 'В обработке';
				$request->time_remaining = NULL;
				$request->admin_id = NULL;
				$json_comment = json_decode($request->comments, true);
				$json_comment[] =
					array(
						'Time' => Carbon::now()->format('d.m.y H:i'),
						'Name' => 'Система',
						'Message' => 'Администратор ' . '"' . Auth::user()->name . '" перевёл заявку в обработку',
					);
				$request->comments = json_encode($json_comment);
				$request->save();
			}
			$user->delete();
			if (Option::find(1)->distributed_requests) {
				RequestService::distribute();
			}
			return redirect('/admin/users');
		}
		abort(404);
	}

	public function change_status($user_id, $status)
	{
		if (Auth::user()->is_admin && Auth::user()->admin->is_master) {
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
							$queue = AdminQueue::where('admin_id', $user->id)->first();
							if (!is_null($queue)) {
								$queue->delete();
							}
							$requests = RequestInfo::where('admin_id', $user->id)->get();
							foreach ($requests as $request) {
								$request->status = 'В обработке';
								$request->time_remaining = NULL;
								$request->admin_id = NULL;
								$json_comment = json_decode($request->comments, true);
								$json_comment[] =
									array(
										'Time' => Carbon::now()->format('d.m.y H:i'),
										'Name' => 'Система',
										'Message' => 'Администратор ' . '"' . Auth::user()->name . '" перевёл заявку в обработку',
									);
								$request->comments = json_encode($json_comment);
								$request->save();
							}
							if (Option::find(1)->distributed_requests) {
								RequestService::distribute();
							}
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
			$distributed_request = AdminQueue::where('request_id', $request->id)->first();
			if (
				($request->status == 'В обработке' ||
					($request->status == 'В работе' &&  ($request->admin_id == Auth::user()->id || Auth::user()->admin->is_master))) &&
				(is_null($distributed_request) || $distributed_request->admin_id == Auth::user()->id || Auth::user()->admin->is_master)
			) {
				$request->status = 'Отменено';
				$request->time_remaining = NULL;
				$request->closed_at = Carbon::now();
				$request->accepted_at = NULL;
				$json_comment = json_decode($request->comments, true);
				$json_comment[] =
					array(
						'Time' => Carbon::now()->format('d.m.y H:i'),
						'Name' => 'Система',
						'Message' => 'Заявка отменена, администратором ' . '"' . Auth::user()->name . '"',
					);
				$request->comments = json_encode($json_comment);
				$request->save();
				TimerService::delete($request);
				if (!is_null($request->admin_id)) {
					RequestService::check_free(Admin::where('user_id', $request->admin_id)->first());
				}
				if (Option::find(1)->distributed_requests) {
					RequestService::clear_request_id($request);
					RequestService::distribute();
				}
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function accept(RequestInfo $request)
	{
		if (Auth::user()->is_admin) {
			$distributed_request = AdminQueue::where('request_id', $request->id)->first();
			if (
				$request->status == 'В обработке' &&
				(is_null($distributed_request) || $distributed_request->admin_id == Auth::user()->id || Auth::user()->admin->is_master)
			) {
				$request->status = 'В работе';
				$request->time_remaining = Option::find(1)->value('time_to_work');
				$request->admin_id = Auth::user()->id;
				$request->accepted_at = Carbon::now();
				$json_comment = json_decode($request->comments, true);
				$json_comment[] =
					array(
						'Time' => Carbon::now()->format('d.m.y H:i'),
						'Name' => 'Система',
						'Message' => 'Заявка принята в работу, администратором ' . '"' . Auth::user()->name . '"',
					);
				$request->comments = json_encode($json_comment);
				$request->save();
				TimerService::create($request);
				RequestService::check_free($request->user_admin->admin);
				if (Option::find(1)->distributed_requests) {
					RequestService::clear_request_id($request);
					RequestService::distribute();
				}
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function deny(RequestInfo $request)
	{
		if (Auth::user()->is_admin) {
			if (($request->admin_id == Auth::user()->id || Auth::user()->admin->is_master) && $request->status == 'В работе') {
				$request->status = 'В обработке';
				$request->time_remaining = NULL;
				$admin_id = $request->admin_id;
				$request->admin_id = NULL;
				$request->accepted_at = NULL;
				$json_comment = json_decode($request->comments, true);
				$json_comment[] =
					array(
						'Time' => Carbon::now()->format('d.m.y H:i'),
						'Name' => 'Система',
						'Message' => 'Администратор ' . '"' . Auth::user()->name . '" перевёл заявку в обработку',
					);
				$request->comments = json_encode($json_comment);
				$request->save();
				TimerService::delete($request);
				if (!is_null($admin_id)) {
					RequestService::check_free(Admin::where('user_id', $admin_id)->first());
				}
				if (Option::find(1)->distributed_requests) {
					RequestService::distribute();
				}
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function complete(RequestInfo $request)
	{
		if (Auth::user()->is_admin) {
			if (($request->admin_id == Auth::user()->id || Auth::user()->admin->is_master) && $request->status == 'В работе') {
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
				if (Option::find(1)->distributed_requests) {
					$admin = $request->user_admin->admin;
					$admin->week_time += Carbon::parse($request->updated_at)->diffInMinutes(Carbon::now());
					$admin->save();
				}
				$request->save();
				TimerService::delete($request);
				RequestService::check_free($request->user_admin->admin);
				if (Option::find(1)->distributed_requests) {
					RequestService::distribute();
				}
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
				$request->accepted_at = NULL;
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
				if (Option::find(1)->distributed_requests) {
					RequestService::distribute();
				}
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function time(RequestInfo $request)
	{
		if (Auth::user()->is_admin) {
			if (($request->admin_id == Auth::user()->id || Auth::user()->admin->is_master) && $request->status == 'В работе') {
				TimerService::addTime($request->job_id, 24);
				$request->time_remaining += 24;
				$request->save();
				return redirect('/admin/requests/' . $request->id)->with('autofocus', true);
			}
			return redirect('/admin/requests/' . $request->id);
		}
		abort(404);
	}

	public function request_destroy(RequestInfo $request)
	{
		if (Auth::user()->is_admin && Auth::user()->admin->is_master) {
			$admin_id = $request->admin_id;
			if (Option::find(1)->distributed_requests) {
				RequestService::clear_request_id($request);
			}
			TimerService::delete($request);
			$request->delete();
			if (!is_null($admin_id)) {
				RequestService::check_free($request->user_admin->admin);
			}
			if (Option::find(1)->distributed_requests) {
				RequestService::distribute();
			}
			return redirect('/admin/requests');
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
		if (Option::find(1)->distributed_requests && Auth::user()->is_admin) {
			$admin = Auth::user()->admin;
			if ($admin->get_recommendation == $request->status) {
				return response()->json(['bad' => 'The set status is the same as the current one.']);
			}
			$admin->get_recommendation = $request->status;
			$admin->save();
			if ($admin->get_recommendation) {
				RequestService::enqueue(Auth::user()->admin);
				RequestService::distribute();
			} else {
				RequestService::dequeue(Auth::user()->admin);
			}
			return response()->json(['success' => 'Recommendation changed successfully.']);
		}
		abort(404);
	}

	public function notification(Request $request)
	{
		if (Auth::user()->is_admin) {
			$admin = Auth::user()->admin;
			if ($admin->sound_notification == $request->status) {
				return response()->json(['bad' => 'The set status is the same as the current one.']);
			}
			$admin->sound_notification = $request->status;
			$admin->save();
			return response()->json(['success' => 'Notification changed successfully.']);
		}
		abort(404);
	}

	public function global(Request $request)
	{
		if (Auth::user()->is_admin  && Auth::user()->admin->is_master) {
			$validateData =  request()->validate([
				'time_to_work' => 'required|integer|max:1000',
				'time_to_accept_distributed' => 'required|integer|max:1000',
				'check_interval' => 'required|integer|min:1000|max:3600000',
				'welcome_text' => 'required|max:4000',

			]);
			if ($request['distributed_requests'] == 'on') {
				$validateData['distributed_requests'] = true;
			} else {
				$validateData['distributed_requests'] = false;
				//1) отключить у всех функцию
				$admins = Admin::all();
				foreach ($admins as $admin) {
					$admin->get_recommendation = false;
					$admin->save();
				}
				//2) удалить все job-распределённые
				DB::table('jobs')->where('queue', 'q2')->delete();
				//3) удалить всю таблицу очередей
				DB::table('admin_queues')->delete();
			}
			$options = Option::find(1);
			$options->update($validateData);
			return redirect('/admin/options')->with('autofocus', true);
		}
		abort(404);
	}

	public function check_new_requests()
	{
		$user = Auth::user();
		if ($user->is_admin && $user->admin->sound_notification) {
			return response()->json(['newCount' => count(RequestInfo::all())]);
		}
		abort(401);
	}

	public function bulk_remove(Request $request)
	{
		if (Auth::user()->is_admin && Auth::user()->admin->is_master) {
			$checked_requests = array();
			foreach ($request->all() as $item => $value) {
				if (str_contains($item, 'check_')) {
					$checked_requests[] = $value;
				}
			}
			switch ($request->input('action')) {
				case 'delete':
					foreach ($checked_requests as $id) {
						$record = RequestInfo::find($id);
						if (!is_null($record)) {
							$this->request_destroy($record);
						}
					}
					break;
				case 'cancel':
					foreach ($checked_requests as $id) {
						$record = RequestInfo::find($id);
						if (!is_null($record)) {
							$this->cancel($record);
						}
					}
					break;
			}
			return redirect('/admin/requests');
		}
		abort(404);
	}
}
