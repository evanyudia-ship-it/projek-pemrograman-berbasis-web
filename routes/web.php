<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;



Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
     ->name('dashboard');

Route::get('/rooms',      [RoomController::class, 'index'])
     ->name('rooms.index');

Route::get('/rooms/{id}', [RoomController::class, 'show'])
     ->name('rooms.show'); 

Route::get('/bookings/create', function () {
    return view('bookings.create');
})->name('bookings.create');

Route::get('/bookings', function () {
    return view('bookings.index');
})->name('bookings.index');

Route::get('/admin/approvals', function () {
    return view('admin.approvals');
})->name('admin.approvals');
