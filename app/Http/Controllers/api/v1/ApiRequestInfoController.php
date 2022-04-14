<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\RequestInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiRequestInfoController extends Controller
{
	private static  $checker = 'accdede43f326c52d88d62b98de5e940'; //md-5 юпитер

	public function index($mac, Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			$json =  RequestInfo::where('mac', $mac)->orderBy('created_at', 'desc')->get();
			foreach ($json as $item) {
				if (!is_null($item->user_admin)) {
					$item['admin'] = $item->user_admin->name;
				}
				$item['beauty_created_at'] = $item->created_at->format('d.m.y H:i');
				if (!is_null($item->closed_at)) {
					$item['beauty_closed_at'] = $item->closed_at->format('d.m.y H:i');
				}
			}
			return $json;
		}
		return abort(404);
	}

	public function info(Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			$json =  Option::select('company_name', 'welcome_text_app')->first();
			return $json;
		}
		return abort(404);
	}
}
