<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\VerificationMailController;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\UserslistController;
use App\Http\Controllers\Users_attendancelistController;
use App\Http\Controllers\AuthenticatedSessionController;

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

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AttendanceController::class, 'input'])->name('input');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

    Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');

    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');

    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.breakStart');

    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.breakEnd');
});

Route::get('/users_list', [UserslistController::class, 'index'])->name('users_attendance_list');

Route::get('/users_attendance_list', [Users_attendancelistController::class, 'index'])->name('users_list');
