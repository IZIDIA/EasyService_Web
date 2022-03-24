<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInfo extends Model
{
	protected $guarded = ['id'];
	protected $dates = ['date_create', 'closed_at'];
	use HasFactory;

	public function pc_info()
	{
		return $this->hasOne(PcInfo::class);
	}

	public function admin_queue()
	{
		return $this->belongsTo(AdminQueue::class);
	}

	public function getUpdatedAtAttribute($date)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('M d, Y H:i:s');
	}
}
