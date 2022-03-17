<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminQueue extends Model
{
	protected $guarded = ['id'];
	use HasFactory;

	//Нет необходимости
	/*
	public function user()
	{
		return $this->hasOne(User::class, 'id'); 
	}

	public function request_info()
	{
		return $this->hasOne(RequestInfo::class, 'admin_id');
	}
	*/
}
