<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;




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
// 一般ユーザー用ログイン
// Route::get('/login', [UserController::class, 'login'])
//     ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// 管理者用ログイン
Route::get('/admin/login', [AdminUserController::class, 'adminLogin'])
    ->name('admin.login');
Route::post('/admin/login', [\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'store']); // Fortify が処理

Route::get('/attendance', [AttendanceController::class, 'create']);

Route::get('/register', [UserController::class, 'create']);

Route::prefix('admin')->group(function () {
    Route::get('/attendance/list', [AdminAttendanceController::class, 'list'])->name('admin.attendance.list');
});
