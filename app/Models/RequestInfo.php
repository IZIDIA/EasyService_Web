<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInfo extends Model
{
	protected $guarded = ['id'];
	protected $dates = ['date_create'];
	use HasFactory;

	public function pc_info()
	{
		return $this->hasOne(PcInfo::class);
	}

	public function admin_queue()
	{
		return $this->belongsTo(AdminQueue::class);
	}
}
