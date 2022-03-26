<?php

use App\Models\Admin;
use App\Models\AdminQueue;
use App\Models\RequestInfo;
use Illuminate\Support\Facades\DB;

class RequestService
{
	public static function enqueue(Admin $admin)
	{
		AdminQueue::create(['admin_id' => $admin->user_id]);
	}

	public static function distribute()
	{
		$requests_id = DB::table('request_infos')
			->leftJoin('admin_queues', 'request_infos.id', '=', 'admin_queues.request_id')
			->select('request_infos.id')
			->whereNull('admin_queues.id')
			->where('status', 'В обработке')
			->orderBy('request_infos.created_at')
			->get();
		//first() ???

		$admins_id = DB::table('admins')
			->leftJoin('admin_queues', 'admins.user_id', '=', 'admin_queues.admin_id')
			->select('admins.user_id')
			->whereNull('admin_queues.id')
			->where('get_recommendation', true)
			->get();

		//Дальше добавить в таблицу очереди?
		if (count($admins_id) > 0) {
			dd($admins_id);
		}
		//$request_info = RequestInfo::create($data);

		/*if ($requests_id != NULL) {
			foreach ($requests_id as $request_id) {
				dd($request_id);
			}
		}*/
	}
}
