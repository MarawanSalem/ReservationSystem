<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ServiceController;
use App\Http\Controllers\Web\ReservationController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User profile routes
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [UserController::class, 'showPasswordForm'])->name('profile.password');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');

    // Service routes
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

    // Reservation routes
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/services/{service}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::put('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::put('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Admin routes
    Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

        // User management routes
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/notify', [AdminUserController::class, 'sendNotification'])->name('users.notify');

        // Service management routes
        Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [AdminServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}', [AdminServiceController::class, 'show'])->name('services.show');
        Route::get('/services/{service}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        // Reservation management routes
        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
        Route::patch('/reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.update-status');
        Route::delete('/reservations/{reservation}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');
    });
});
