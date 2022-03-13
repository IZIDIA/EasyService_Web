<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminQueue extends Model
{
	protected $guarded = ['id'];
	use HasFactory;

	public function user()
	{
		return $this->hasOne(User::class);
	}

	public function request_info()
	{
		return $this->hasOne(Request::class);
	}
}
