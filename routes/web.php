<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\HelpController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoomManageController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\OrganizationApprovalController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\AdminFacultyController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'process'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'process'])->name('register.process');

Route::get('/verify', [VerifyController::class, 'show'])->name('verify.show');
Route::post('/verify', [VerifyController::class, 'process'])->name('verify.process');
Route::post('/verify/resend', [VerifyController::class, 'resend'])->name('verify.resend');


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.session'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:mahasiswa,dosen'])->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    });


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');


    /*
    |--------------------------------------------------------------------------
    | Rooms
    |--------------------------------------------------------------------------
    */

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');


    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');


    /*
    |--------------------------------------------------------------------------
    | Schedule
    |--------------------------------------------------------------------------
    */

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');


    /*
    |--------------------------------------------------------------------------
    | Reputation
    |--------------------------------------------------------------------------
    */

    Route::prefix('reputation')->name('reputation.')->group(function () {
        Route::get('/', function () {
            return view('reputation.index', [
                'reputation_point' => 85,
            ]);
        })->name('index');
    });


    /*
    |--------------------------------------------------------------------------
    | Organization
    |--------------------------------------------------------------------------
    */

    Route::prefix('organization')->name('organization.')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('index');
        Route::get('/create', [OrganizationController::class, 'create'])->name('create');
        Route::post('/', [OrganizationController::class, 'store'])->name('store');
        Route::delete('/{id}/cancel', [OrganizationController::class, 'cancel'])->name('cancel');
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

    Route::middleware(['role:admin,superadmin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Admin Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | Approval Booking
            |--------------------------------------------------------------------------
            */

            Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals');
            Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
            Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');


            /*
            |--------------------------------------------------------------------------
            | Organization Approval
            |--------------------------------------------------------------------------
            */

            Route::prefix('organization-approvals')->name('organization-approvals.')->group(function () {
                Route::get('/', [OrganizationApprovalController::class, 'index'])->name('index');
                Route::get('/{id}', [OrganizationApprovalController::class, 'show'])->name('show');
                Route::post('/{id}/approve', [OrganizationApprovalController::class, 'approve'])->name('approve');
                Route::post('/{id}/reject', [OrganizationApprovalController::class, 'reject'])->name('reject');
                Route::get('/{id}/download', [OrganizationApprovalController::class, 'downloadFile'])->name('download');
            });


            /*
            |--------------------------------------------------------------------------
            | Manage Rooms
            |--------------------------------------------------------------------------
            */

            Route::get('/rooms', [RoomManageController::class, 'index'])->name('rooms.index');
            Route::post('/rooms', [RoomManageController::class, 'store'])->name('rooms.store');
            Route::put('/rooms/{id}', [RoomManageController::class, 'update'])->name('rooms.update');
            Route::delete('/rooms/{id}', [RoomManageController::class, 'destroy'])->name('rooms.destroy');
            Route::post('/rooms/{id}/toggle', [RoomManageController::class, 'toggleStatus'])->name('rooms.toggleStatus');
            Route::post('/rooms/reset', [RoomManageController::class, 'reset'])->name('rooms.reset');


            /*
            |--------------------------------------------------------------------------
            | Manage Users
            |--------------------------------------------------------------------------
            */

            Route::resource('users', UserController::class);


            /*
            |--------------------------------------------------------------------------
            | Manage Faculties
            |--------------------------------------------------------------------------
            */

            Route::resource('faculties', FacultyController::class)->except(['show']);


            /*
            |--------------------------------------------------------------------------
            | Manage Admin Faculties
            |--------------------------------------------------------------------------
            */

            Route::get('/admin-faculties', [AdminFacultyController::class, 'index'])->name('admin-faculties.index');
            Route::post('/admin-faculties', [AdminFacultyController::class, 'store'])->name('admin-faculties.store');
            Route::delete('/admin-faculties/{adminFaculty}', [AdminFacultyController::class, 'destroy'])->name('admin-faculties.destroy');
        });
});