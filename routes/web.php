<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/rooms', function () {
    return view('rooms.index');
})->name('rooms.index');

Route::get('/bookings/create', function () {
    return view('bookings.create');
})->name('bookings.create');

Route::get('/bookings', function () {
    return view('bookings.index');
})->name('bookings.index');

Route::get('/admin/approvals', function () {
    return view('admin.approvals');
})->name('admin.approvals');
