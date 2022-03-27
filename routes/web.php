<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RequestInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('welcome');
});

Route::get('/docs', function () {
	return view('docs');
});

Route::get('/contacts', function () {
	return view('contacts');
});

require __DIR__ . '/auth.php';

//Администратор
Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth']);
Route::get('/admin/users', [AdminController::class, 'users'])->middleware(['auth']);
Route::get('/admin/users/{user}', [AdminController::class, 'user_show'])->middleware(['auth']);
Route::get('/admin/my', [AdminController::class, 'my'])->middleware(['auth']);
Route::get('/admin/requests', [AdminController::class, 'requests'])->middleware(['auth']);
Route::get('/admin/requests/{request}', [AdminController::class, 'request_show'])->middleware(['auth']);
Route::patch('/admin/requests/{request}/comment', [AdminController::class, 'comment'])->middleware(['auth']);
Route::get('/admin/requests/{request}/comment', function () {
	abort(404);
});
Route::patch('/admin/requests/{request}/cancel', [AdminController::class, 'cancel'])->middleware(['auth']);
Route::get('/admin/requests/{request}/cancel', function () {
	abort(404);
});
Route::patch('/admin/requests/{request}/accept', [AdminController::class, 'accept'])->middleware(['auth']);
Route::get('/admin/requests/{request}/accept', function () {
	abort(404);
});
Route::patch('/admin/requests/{request}/deny', [AdminController::class, 'deny'])->middleware(['auth']);
Route::get('/admin/requests/{request}/deny', function () {
	abort(404);
});
Route::patch('/admin/requests/{request}/complete', [AdminController::class, 'complete'])->middleware(['auth']);
Route::get('/admin/requests/{request}/complete', function () {
	abort(404);
});
Route::patch('/admin/requests/{request}/restore', [AdminController::class, 'restore'])->middleware(['auth']);
Route::get('/admin/requests/{request}/restore', function () {
	abort(404);
});
Route::patch('/admin/requests/{request}/time', [AdminController::class, 'time'])->middleware(['auth']);
Route::get('/admin/requests/{request}/time', function () {
	abort(404);
});
Route::delete('/admin/requests/{request}', [AdminController::class, 'destroy'])->middleware('auth');

//Пользователь
Route::get('/requests', [RequestInfoController::class, 'index'])->name('requests.index');
Route::get('/requests/create', [RequestInfoController::class, 'create'])->name('requests.create');
Route::post('/requests', [RequestInfoController::class, 'store'])->name('requests.store');
Route::get('/requests/{request}', [RequestInfoController::class, 'show'])->name('requests.show');
Route::patch('/requests/{request}/cancel', [RequestInfoController::class, 'cancel']);
Route::get('/requests/{request}/cancel', function () {
	abort(404);
});
Route::patch('/requests/{request}/comment', [RequestInfoController::class, 'comment']);
Route::get('/requests/{request}/comment', function () {
	abort(404);
});

Route::get('/test', function () {
	RequestService::distribute();
});
