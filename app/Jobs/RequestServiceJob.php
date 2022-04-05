<?php

namespace App\Jobs;

use App\Models\AdminQueue;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RequestServiceJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $adminId;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($adminId)
	{
		$this->adminId = $adminId;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle(AdminQueue $adminQueue)
	{
		$record = $adminQueue->where('admin_id', $this->adminId);
		if (!is_null($record->first()) && $this->job->getJobId() == $record->first()->job_id) {
			$admin = User::findOrFail($this->adminId)->admin;
			$admin->get_recommendation = false;
			$admin->save();
			$record->delete();
		}
	}
}
