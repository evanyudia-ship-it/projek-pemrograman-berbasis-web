<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApprovalController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', fn() => redirect()->route('dashboard'))->name('login.process');
Route::get('/logout', fn() => redirect()->route('login'))->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Rooms
|--------------------------------------------------------------------------
*/
Route::get('/rooms',       [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{id}',  [RoomController::class, 'show'])->name('rooms.show');

/*
|--------------------------------------------------------------------------
| Bookings
|--------------------------------------------------------------------------
*/
Route::get('/bookings',        fn() => view('bookings.index'))->name('bookings.index');
Route::get('/bookings/create', fn() => view('bookings.create'))->name('bookings.create');

/*
|--------------------------------------------------------------------------
| Schedule
|--------------------------------------------------------------------------
*/
Route::get('/schedule', [ScheduleController::class, 'index'])
    ->name('schedule.index');
    
/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile.index');

/*
|--------------------------------------------------------------------------
| Reputation
|--------------------------------------------------------------------------
*/
Route::prefix('reputation')->name('reputation.')->group(function () {
    Route::get('/', function () {
        return view('reputation.index', ['reputation_point' => 85]);
    })->name('index');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals');
    Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', fn() => view('admin.users.index'))->name('index');
    });

});