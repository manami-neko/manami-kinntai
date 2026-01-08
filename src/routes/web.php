<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\BreakTimeController;
use App\Http\Controllers\CorrectionRequestController;
use App\Http\Controllers\Admin\CorrectionRequestController as AdminCorrectionRequestController;




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

// 管理者用ログイン画面
Route::get('/admin/login', [AdminUserController::class, 'adminLogin'])->name('admin.login');
// Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);


Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'create']);

    Route::prefix('admin')->group(function () {
        Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.list');
    });
    // 出勤登録画面
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');

    // 出勤
    Route::post('/attendance/start', [AttendanceController::class, 'store'])->name('attendance.start');

    // 退勤
    Route::patch('/attendance/end', [AttendanceController::class, 'update'])->name('attendance.end');

    // 休憩開始
    Route::post('/break/start', [BreakTimeController::class, 'store'])->name('break.start');

    // 休憩終了
    Route::patch('/break/end', [BreakTimeController::class, 'update'])->name('break.end');

    Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.list');

    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show'])->name('users.show');

    Route::post('/attendance/detail/{id}', [CorrectionRequestController::class, 'store']);

    Route::get('/stamp_correction_request/list', [CorrectionRequestController::class, 'userList'])->name('users.request');

    Route::get('/admin/attendance/detail/{id}', [AdminAttendanceController::class, 'show'])->name('admin.attendance.show');

    Route::put('/admin/attendance/detail/{id}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');

    Route::get('/admin/staff/list', [AdminUserController::class, 'staffList']);

    Route::get('/admin/attendance/staff/{id}', [AdminAttendanceController::class, 'staffAttendanceList'])->name('admin.attendance.user');

    Route::get('/admin/stamp_correction_request/list', [AdminCorrectionRequestController::class, 'userList']);

    Route::get('/admin/stamp_correction_request/approve/{attendance_correct_request_id}',[AdminCorrectionRequestController::class, 'show'])->name('admin.show');

    Route::put('/admin/stamp_correction_request/approve/{attendance_correct_request_id}',[AdminCorrectionRequestController::class, 'approve'])->name('admin.approve');

});
