<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;

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
| Profile
|--------------------------------------------------------------------------
*/
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', fn() => view('profile.index'))->name('index');
});

/*
|--------------------------------------------------------------------------
| Reputation
|--------------------------------------------------------------------------
*/
Route::prefix('reputation')->name('reputation.')->group(function () {
    Route::get('/', function () {
        return view('reputation', ['reputation_point' => 85]);
    })->name('index');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/approvals', fn() => view('admin.approvals'))->name('approvals');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', fn() => view('admin.users.index'))->name('index');
    });

});