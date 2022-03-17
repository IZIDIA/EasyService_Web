<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RequestInfoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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



Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth'])->name('admin.index');
Route::get('/admin/my', [AdminController::class, 'my'])->middleware(['auth'])->name('admin.my');
Route::get('/admin/requests', [AdminController::class, 'requests'])->middleware(['auth'])->name('admin.requests.index');
Route::get('/admin/requests/{request}', [AdminController::class, 'show'])->middleware(['auth'])->name('admin.requests.show');

Route::get('/requests', [RequestInfoController::class, 'index'])->name('requests.index');
Route::get('/requests/create', [RequestInfoController::class, 'create'])->name('requests.create');
Route::post('/requests', [RequestInfoController::class, 'store'])->name('requests.store');
Route::get('/requests/{request}', [RequestInfoController::class, 'show'])->name('requests.show');
