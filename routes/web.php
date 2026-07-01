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
use App\Http\Controllers\BookingCancellationController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReputationController;
use App\Http\Controllers\BannedController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoomManageController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\OrganizationApprovalController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\AdminFacultyController;
use App\Http\Controllers\Admin\FacilityController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'process'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'process'])->name('register.process');

Route::get('/verify', [VerifyController::class, 'show'])->name('verify.show');
Route::post('/verify', [VerifyController::class, 'process'])->name('verify.process');
Route::post('/verify/resend', [VerifyController::class, 'resend'])->name('verify.resend');

/*
|--------------------------------------------------------------------------
| Banned Routes (Akses sebelum middleware banned)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/banned', [BannedController::class, 'index'])->name('banned.index');
    Route::post('/banned/appeal', [BannedController::class, 'appeal'])->name('banned.appeal');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required + Banned Check)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:superadmin');

    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('admin.dashboard')
        ->middleware('role:admin');

    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
        ->name('user.dashboard')
        ->middleware('role:mahasiswa,dosen,organisasi');

    Route::get('/dashboard/redirect', [DashboardController::class, 'redirectByRole'])
        ->name('dashboard.redirect');


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
    | Rooms (Public)
    |--------------------------------------------------------------------------
    */

    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/search', [RoomController::class, 'search'])->name('search');
        Route::get('/{id}', [RoomController::class, 'show'])->name('show');
    });


    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/history', [BookingController::class, 'history'])->name('history');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BookingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BookingController::class, 'update'])->name('update');
        Route::post('/{id}/checkin', [BookingController::class, 'checkin'])->name('checkin');
        Route::post('/{id}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::get('/{id}/cancel-form', [BookingCancellationController::class, 'create'])->name('cancel.create');
        Route::post('/{id}/cancel-process', [BookingCancellationController::class, 'store'])->name('cancel.store');
        Route::post('/{id}/complete', [BookingController::class, 'complete'])->name('complete');
    });


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
        Route::get('/', [ReputationController::class, 'index'])->name('index');
    });


    /*
    |--------------------------------------------------------------------------
    | Help
    |--------------------------------------------------------------------------
    */

    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', [HelpController::class, 'index'])->name('index');
        Route::get('/search', [HelpController::class, 'search'])->name('search');
        Route::get('/article/{slug}', [HelpController::class, 'article'])->name('article');
        Route::get('/category/{slug}', [HelpController::class, 'category'])->name('category');
    });


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');

        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/delete-read', [NotificationController::class, 'deleteAllRead'])->name('delete-read');
        Route::delete('/delete-read/force', [NotificationController::class, 'forceDeleteAllRead'])->name('force-delete-read');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');

        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/{id}/force', [NotificationController::class, 'forceDelete'])->name('force-delete');
    });


    /*
    |--------------------------------------------------------------------------
    | Admin Section
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin,superadmin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | Approval Booking
            |--------------------------------------------------------------------------
            */

            Route::prefix('approvals')->name('approvals.')->group(function () {
                Route::get('/', [ApprovalController::class, 'index'])->name('index');
                Route::post('/{id}/approve', [ApprovalController::class, 'approve'])->name('approve');
                Route::post('/{id}/reject', [ApprovalController::class, 'reject'])->name('reject');
            });


            /*
            |--------------------------------------------------------------------------
            | Admin Bookings
            |--------------------------------------------------------------------------
            */

            Route::prefix('bookings')->name('bookings.')->group(function () {
                Route::get('/', [BookingAdminController::class, 'index'])->name('index');
                Route::get('/{id}', [BookingAdminController::class, 'show'])->name('show');
                Route::post('/{id}/mark-no-show', [BookingAdminController::class, 'markNoShow'])->name('mark-no-show');
                Route::post('/{id}/mark-fake', [BookingAdminController::class, 'markFakeBooking'])->name('mark-fake');
            });


            /*
            |--------------------------------------------------------------------------
            | Manage Rooms
            |--------------------------------------------------------------------------
            */

            Route::prefix('rooms')->name('rooms.')->group(function () {
                Route::get('/', [RoomManageController::class, 'index'])->name('index');
                Route::get('/create', [RoomManageController::class, 'create'])->name('create');
                Route::post('/', [RoomManageController::class, 'store'])->name('store');
                Route::get('/{id}', [RoomManageController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [RoomManageController::class, 'edit'])->name('edit');
                Route::put('/{id}', [RoomManageController::class, 'update'])->name('update');
                Route::delete('/{id}', [RoomManageController::class, 'destroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [RoomManageController::class, 'toggleStatus'])->name('toggle-status');
                Route::post('/reset', [RoomManageController::class, 'reset'])->name('reset');
            });


            /*
            |--------------------------------------------------------------------------
            | SUPER ADMIN ONLY
            |--------------------------------------------------------------------------
            */

            Route::middleware(['role:superadmin'])->group(function () {

                Route::resource('users', UserController::class);


                Route::prefix('facilities')->name('facilities.')->group(function () {
                    Route::get('/', [FacilityController::class, 'index'])->name('index');
                    Route::get('/create', [FacilityController::class, 'create'])->name('create');
                    Route::post('/', [FacilityController::class, 'store'])->name('store');
                    Route::get('/{id}', [FacilityController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [FacilityController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [FacilityController::class, 'update'])->name('update');
                    Route::delete('/{id}', [FacilityController::class, 'destroy'])->name('destroy');
                    Route::get('/{id}/rooms', [FacilityController::class, 'getRooms'])->name('rooms');
                });


                // ✅ PERBAIKAN: Hapus ->except(['show'])
                Route::get('/faculties', [FacultyController::class, 'index'])->name('faculties.index');
                Route::get('/faculties/create', [FacultyController::class, 'create'])->name('faculties.create');
                Route::post('/faculties', [FacultyController::class, 'store'])->name('faculties.store');
                Route::get('/faculties/{faculty}', [FacultyController::class, 'show'])->name('faculties.show');
                Route::get('/faculties/{faculty}/edit', [FacultyController::class, 'edit'])->name('faculties.edit');
                Route::put('/faculties/{faculty}', [FacultyController::class, 'update'])->name('faculties.update');
                Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy'])->name('faculties.destroy');


                Route::prefix('admin-faculties')->name('admin-faculties.')->group(function () {
                    Route::get('/', [AdminFacultyController::class, 'index'])->name('index');
                    Route::post('/', [AdminFacultyController::class, 'store'])->name('store');
                    Route::get('/{adminFaculty}/edit', [AdminFacultyController::class, 'edit'])->name('edit');
                    Route::put('/{adminFaculty}', [AdminFacultyController::class, 'update'])->name('update');
                    Route::delete('/{adminFaculty}', [AdminFacultyController::class, 'destroy'])->name('destroy');
                });


                Route::prefix('reputation')->name('reputation.')->group(function () {
                    Route::get('/settings', [ReputationController::class, 'settings'])->name('settings');
                    Route::put('/settings', [ReputationController::class, 'updateSettings'])->name('settings.update');
                    Route::get('/logs', [ReputationController::class, 'logs'])->name('logs');
                    Route::get('/levels', [ReputationController::class, 'levels'])->name('levels');
                    Route::put('/levels/{id}', [ReputationController::class, 'updateLevel'])->name('levels.update');
                });


                Route::prefix('appeals')->name('appeals.')->group(function () {
                    Route::get('/', [BannedController::class, 'adminIndex'])->name('index');
                    Route::get('/{id}', [BannedController::class, 'adminShow'])->name('show');
                    Route::post('/{id}/approve', [BannedController::class, 'adminApprove'])->name('approve');
                    Route::post('/{id}/reject', [BannedController::class, 'adminReject'])->name('reject');
                });

            }); // End SuperAdmin Only

        });

});
