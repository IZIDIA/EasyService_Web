<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Option;
use App\Models\RequestInfo;
use Carbon\Carbon;
use Dotenv\Util\Str;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use RequestService;

class RequestInfoController extends Controller
{
	public function index()
	{
		if (Auth::check()) {
			$request_infos = RequestInfo::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
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
				$data['work_time'] = [];
				if ($request['monday'] == 'on') {
					$data['work_time'][] = array(
						'monday' =>
						array(
							'from' => $request['from_monday'],
							'to' => $request['to_monday'],
						),
					);
				}
				if ($request['tuesday'] == 'on') {
					$data['work_time'][] = array(
						'tuesday' =>
						array(
							'from' => $request['from_tuesday'],
							'to' => $request['to_tuesday'],
						),
					);
				}
				if ($request['wednesday'] == 'on') {
					$data['work_time'][] = array(
						'wednesday' =>
						array(
							'from' => $request['from_wednesday'],
							'to' => $request['to_wednesday'],
						),
					);
				}
				if ($request['thursday'] == 'on') {
					$data['work_time'][] = array(
						'thursday' =>
						array(
							'from' => $request['from_thursday'],
							'to' => $request['to_thursday'],
						),
					);
				}
				if ($request['friday'] == 'on') {
					$data['work_time'][] = array(
						'friday' =>
						array(
							'from' => $request['from_friday'],
							'to' => $request['to_friday'],
						),
					);
				}
				if ($request['saturday'] == 'on') {
					$data['work_time'][] = array(
						'saturday' =>
						array(
							'from' => $request['from_saturday'],
							'to' => $request['to_saturday'],
						),
					);
				}
				if ($request['sunday'] == 'on') {
					$data['work_time'][] = array(
						'sunday' =>
						array(
							'from' => $request['from_sunday'],
							'to' => $request['to_sunday'],
						),
					);
				}
				$data['work_time'] = json_encode($data['work_time']);
			}
		} else {
			$data = $this->anonymValidateData();
			$data['name'] = 'Аноним';
			$data['email'] = NULL;
			$data['phone_call_number'] = NULL;
		}
		if ($request['problem_with_my_pc'] == 'on') {
			$data['problem_with_my_pc'] = true;
			$data['user_password'] = $request->user_password;
		} else {
			$data['problem_with_my_pc'] = false;
		}
		$data['from_pc'] = false;
		$data['ip_address'] = request()->ip();
		$data['status'] = 'В обработке';
		$json_comment = json_encode(array(
			0 =>
			array(
				'Time' => Carbon::now()->format('d.m.y H:i'),
				'Name' => 'Система',
				'Message' => 'Заявка создана',
			),
		));
		$data['comments'] = $json_comment;
		if (Auth::check()) {
			$data['user_id'] = Auth::user()->id;
			$request_info = RequestInfo::create($data);
			$this->storeImage($request_info);
			RequestService::distribute();
			return redirect('/requests/');
		}
		$data['session_id'] = session()->getId();
		$request_info = RequestInfo::create($data);
		$this->storeImage($request_info);
		RequestService::distribute();
		return redirect('/requests/' . $request_info->id);
	}

	protected function validateData()
	{
		return tap($validateData =  request()->validate([
			'solution_with_me' => 'required',
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
					'photo' => 'file|image|max:10000'
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
					'photo' => 'file|image|max:10000'
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
		if ($request_info->session_id == session()->getId() || (Auth::check() && (Auth::user()->id == $request_info->user_id || Auth::user()->is_admin))) {
			$work_time = '';
			if (!empty($request_info->work_time)) {
				foreach (json_decode($request_info->work_time) as $first_key => $first_val) {
					foreach ($first_val as $second_key => $second_val) {
						if ($second_key === 'monday') {
							$work_time .= 'ПН ' . 'С: ' . $second_val->from .  ' До: ' . $second_val->to . PHP_EOL;
						}
						if ($second_key === 'tuesday') {
							$work_time .= 'ВТ ' . 'С: ' . $second_val->from .  ' До: ' . $second_val->to . PHP_EOL;
						}
						if ($second_key === 'wednesday') {
							$work_time .= 'СР ' . 'С: ' . $second_val->from .  ' До: ' . $second_val->to . PHP_EOL;
						}
						if ($second_key === 'thursday') {
							$work_time .= 'ЧТ ' . 'С: ' . $second_val->from .  ' До: ' . $second_val->to . PHP_EOL;
						}
						if ($second_key === 'friday') {
							$work_time .= 'ПТ ' . 'С: ' . $second_val->from .  ' До: ' . $second_val->to . PHP_EOL;
						}
						if ($second_key === 'saturday') {
							$work_time .= 'СБ ' . 'С: ' . $second_val->from .  ' До: ' . $second_val->to . PHP_EOL;
						}
						if ($second_key === 'sunday') {
							$work_time .= 'ВС ' . 'С: ' . $second_val->from .  ' До: ' . $second_val->to . PHP_EOL;
						}
					}
				}
			}
			return view('requests.show', compact('request_info'))
				->with('comments', json_decode($request_info->comments, true))
				->with('work_time', $work_time, true);
		}
		abort(404);
	}

	public function cancel(RequestInfo $request)
	{
		if ($request->session_id == session()->getId() || (Auth::check() && Auth::user()->id == $request->user_id)) {
			$request->status = 'Отменено';
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Заявка отменена, заявителем ' . '"' . $request->name . '"',
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			if (!is_null($request->admin_id)) {
				RequestService::check_free(Admin::where('user_id', 	$request->admin_id)->first());
			}
			if (Option::find(1)->distributed_requests) {
				RequestService::clear_request_id($request);
				RequestService::distribute();
			}
			return redirect('/requests/' . $request->id);
		}
		abort(404);
	}

	public function comment(RequestInfo $request)
	{
		if ($request->session_id == session()->getId() || (Auth::check() && Auth::user()->id == $request->user_id)) {
			$data = request()->validate([
				'comment_text' => 'required|min:1|max:512',
			]);
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => $request->name,
					'Message' => str_replace(PHP_EOL, ' ', $data['comment_text']),
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			return redirect('/requests/' . $request->id);
		}
		abort(404);
	}

	public function search(Request $request)
	{
		$search = $request->input('query');
		if (strlen($search) > 10) {
			return redirect('/requests');
		}
		$request_infos = RequestInfo::where('id', $search)->get();
		return view('requests.search', [
			'request_infos' => $request_infos,
			'search' => $search,
		]);
	}
}
