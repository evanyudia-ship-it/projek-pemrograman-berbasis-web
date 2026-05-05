<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\RoomManageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/',       fn() => redirect()->route('login'));
Route::get('/login',  fn() => view('auth.login'))->name('login');
Route::post('/login', [LoginController::class, 'process'])->name('login.process');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

// Register
Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'process'])->name('register.process');

// Verify OTP
Route::get('/verify',        [VerifyController::class, 'show'])->name('verify.show');
Route::post('/verify',       [VerifyController::class, 'process'])->name('verify.process');
Route::post('/verify/resend',[VerifyController::class, 'resend'])->name('verify.resend');

/*
|--------------------------------------------------------------------------
| SuperAdmin Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| User Dashboard (Mahasiswa & Dosen)
|--------------------------------------------------------------------------
*/
Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

/*
|--------------------------------------------------------------------------
| Rooms
|--------------------------------------------------------------------------
*/
Route::get('/rooms',      [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');

/*
|--------------------------------------------------------------------------
| Bookings
|--------------------------------------------------------------------------
*/
Route::get('/bookings',              [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/create',       [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings',             [BookingController::class, 'store'])->name('bookings.store');
Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

/*
|--------------------------------------------------------------------------
| Schedule
|--------------------------------------------------------------------------
*/
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

/*
|--------------------------------------------------------------------------
| Reputation
|--------------------------------------------------------------------------
*/
Route::prefix('reputation')->name('reputation.')->group(function () {
    Route::get('/', fn() => view('reputation.index', ['reputation_point' => 85]))->name('index');
});

/*
|--------------------------------------------------------------------------
| Help
|--------------------------------------------------------------------------
*/
Route::get('/help', [HelpController::class, 'index'])->name('help.index');

/*
|--------------------------------------------------------------------------
| Admin Section
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Approvals
    Route::get('/approvals',                   [ApprovalController::class, 'index'])->name('approvals');
    Route::post('/approvals/{id}/approve',     [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{id}/reject',      [ApprovalController::class, 'reject'])->name('approvals.reject');

    // Manage Rooms
    Route::get('/rooms',              [RoomManageController::class, 'index'])->name('rooms.index');
    Route::post('/rooms',             [RoomManageController::class, 'store'])->name('rooms.store');
    Route::put('/rooms/{id}',         [RoomManageController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{id}',      [RoomManageController::class, 'destroy'])->name('rooms.destroy');
    Route::post('/rooms/{id}/toggle', [RoomManageController::class, 'toggleStatus'])->name('rooms.toggleStatus');
    Route::post('/rooms/reset',       [RoomManageController::class, 'reset'])->name('rooms.reset');

    // Manage Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', fn() => view('admin.users.index'))->name('index');
    });

});