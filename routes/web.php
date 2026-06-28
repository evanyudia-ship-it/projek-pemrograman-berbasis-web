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
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReputationController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoomManageController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\OrganizationApprovalController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\AdminFacultyController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\RoomFacilityController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ===== LOGIN ROUTES =====
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'process'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== REGISTER ROUTES =====
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'process'])->name('register.process');

// ===== VERIFY ROUTES =====
Route::get('/verify', [VerifyController::class, 'show'])->name('verify.show');
Route::post('/verify', [VerifyController::class, 'process'])->name('verify.process');
Route::post('/verify/resend', [VerifyController::class, 'resend'])->name('verify.resend');


/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    // Super Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:superadmin');

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('admin.dashboard')
        ->middleware('role:admin');

    // User Dashboard (Mahasiswa/Dosen)
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
        ->name('user.dashboard')
        ->middleware('role:mahasiswa,dosen');

    // Fallback: redirect ke dashboard yang sesuai
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

        // Cancellation via separate controller
        Route::get('/{id}/cancel-form', [BookingCancellationController::class, 'create'])->name('cancel.create');
        Route::post('/{id}/cancel-process', [BookingCancellationController::class, 'store'])->name('cancel.store');
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
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/delete-read', [NotificationController::class, 'deleteAllRead'])->name('delete-read');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
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
            });


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
            | Manage Rooms (Admin)
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
            | Manage Facilities
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Manage Room Facilities
            |--------------------------------------------------------------------------
            */

            Route::prefix('room-facilities')->name('room-facilities.')->group(function () {
                Route::get('/', [RoomFacilityController::class, 'index'])->name('index');
                Route::post('/attach', [RoomFacilityController::class, 'attach'])->name('attach');
                Route::post('/bulk-attach', [RoomFacilityController::class, 'bulkAttach'])->name('bulk-attach');
                Route::put('/{roomId}/{facilityId}', [RoomFacilityController::class, 'update'])->name('update');
                Route::delete('/{roomId}/{facilityId}', [RoomFacilityController::class, 'detach'])->name('detach');
                Route::get('/{roomId}/facilities', [RoomFacilityController::class, 'getRoomFacilities'])->name('get-room-facilities');
                Route::get('/{roomId}/available', [RoomFacilityController::class, 'getAvailableFacilities'])->name('get-available');
            });


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

            Route::prefix('admin-faculties')->name('admin-faculties.')->group(function () {
                Route::get('/', [AdminFacultyController::class, 'index'])->name('index');
                Route::post('/', [AdminFacultyController::class, 'store'])->name('store');
                Route::delete('/{adminFaculty}', [AdminFacultyController::class, 'destroy'])->name('destroy');
            });


            /*
            |--------------------------------------------------------------------------
            | Manage Users
            |--------------------------------------------------------------------------
            */

            Route::resource('users', UserController::class);


            /*
            |--------------------------------------------------------------------------
            | Reputation Management (Admin)
            |--------------------------------------------------------------------------
            */

            Route::prefix('reputation')->name('reputation.')->group(function () {
                Route::get('/settings', [ReputationController::class, 'settings'])->name('settings');
                Route::put('/settings', [ReputationController::class, 'updateSettings'])->name('settings.update');
                Route::get('/logs', [ReputationController::class, 'logs'])->name('logs');
                Route::get('/levels', [ReputationController::class, 'levels'])->name('levels');
                Route::put('/levels/{id}', [ReputationController::class, 'updateLevel'])->name('levels.update');
            });

        });

});
