<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\RequestInfo;
use Illuminate\Http\Request;

class ApiRequestInfoController extends Controller
{
	private static  $checker = 'accdede43f326c52d88d62b98de5e940';
	public function index($mac, Request $request)
	{
		if ($request->header('Checker') == self::$checker) {
			return RequestInfo::where('mac', $mac)->get();
		}
		return abort(404);
	}
}
