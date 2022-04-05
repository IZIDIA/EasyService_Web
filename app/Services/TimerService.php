<?php

use App\Jobs\RequestServiceJob;
use App\Jobs\TimerServiceJob;
use App\Models\Admin;
use App\Models\AdminQueue;
use App\Models\Option;
use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Bus\Dispatcher;

class TimerService
{
	//При взятии заявки
	public static function create(RequestInfo $request)
	{
		$job = (new TimerServiceJob($request->id))->onQueue('q1')->delay(now()->addHours($request->time_remaining));
		$request->job_id = app(Dispatcher::class)->dispatch($job);
		$request->save();
	}
	//При завершении, отказа, отмене заявки.
	public static function delete(RequestInfo $request)
	{
		$job = DB::table('jobs')->where('id', $request->job_id);
		if ($job->exists()) {
			$job->delete();
		}
	}

	public static function addTime($jobId, $hours)
	{
		$job = DB::table('jobs')->where('id', $jobId);
		if ($job->exists()) {
			$job->update(array('available_at' => $job->first()->available_at + $hours * 3600));
		}
	}
}
