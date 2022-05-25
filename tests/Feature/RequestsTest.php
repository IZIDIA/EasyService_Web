<?php

namespace Tests\Feature;

use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RequestsTest extends TestCase
{
	//	use RefreshDatabase;

	public function test_api_users_can_create()
	{
		for ($i = 1; $i <= 100; $i++) {
			$request_info = RequestInfo::factory()->create();
		}
	}
}
