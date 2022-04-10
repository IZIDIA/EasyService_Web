<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInfo extends Model
{
	protected $guarded = ['id'];
	protected $dates = ['accepted_at', 'closed_at'];
	use HasFactory;

	public function user_admin()
	{
		return $this->belongsTo(User::class, 'admin_id');
	}

	/*public function pc_info()
	{
		return $this->hasOne(PcInfo::class, 'request_info_id');
	}*/

	public function admin_queue()
	{
		return $this->hasOne(AdminQueue::class, 'request_id');
	}

}
