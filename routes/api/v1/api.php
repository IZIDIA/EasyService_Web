<?php

use App\Http\Controllers\api\v1\ApiRequestInfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api'])->group(function () {
	Route::get('/{mac}/requests', [ApiRequestInfoController::class, 'index']);
	Route::get('/{mac}/{id}', [ApiRequestInfoController::class, 'show']);
	Route::post('/{mac}/{id}/comment', [ApiRequestInfoController::class, 'comment']);
	Route::get('/{mac}/{id}/cancel', [ApiRequestInfoController::class, 'cancel']);
	Route::get('/info', [ApiRequestInfoController::class, 'info']);
	//Route::middleware(['throttle:uploads'])->group(function () {
	Route::post('/requests', [ApiRequestInfoController::class, 'store']);
	//	});
});
