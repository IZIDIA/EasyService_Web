<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Models\Option;
use App\Models\RequestInfo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RequestService;

class TimerServiceJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $requestId;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($requestId)
	{
		$this->requestId = $requestId;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(RequestInfo $request_info)
	{
		$request = $request_info->find($this->requestId);
		if (!is_null($request) && $request->status == 'В работе' && $this->job->getJobId() == $request->job_id) {
			$request->status = 'В обработке';
			$request->time_remaining = NULL;
			$admin_id = $request->admin_id;
			$request->admin_id = NULL;
			$json_comment = json_decode($request->comments, true);
			$json_comment[] =
				array(
					'Time' => Carbon::now()->format('d.m.y H:i'),
					'Name' => 'Система',
					'Message' => 'Время на выполнение заявки истекло. Заявка переведена в обработку',
				);
			$request->comments = json_encode($json_comment);
			$request->save();
			if (!is_null($admin_id)) {
				RequestService::check_free(Admin::where('user_id', $admin_id)->first());
			}
			if (Option::find(1)->distributed_requests) {
				RequestService::distribute();
			}
		}
	}
}
