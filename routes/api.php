<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\V1\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Api\V1\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Api\V1\Admin\NotificationController as AdminNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // User profile routes
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/password', [UserController::class, 'updatePassword']);

        // Service Routes
        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{service}', [ServiceController::class, 'show']);

        // Reservation Routes
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/services/{service}/reservations', [ReservationController::class, 'store']);
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

        // Notification Routes
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::put('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
        Route::put('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);

        // Admin Routes
        Route::middleware('role:admin')->prefix('admin')->group(function () {
            // Profile Routes
            Route::get('/profile', [AdminProfileController::class, 'show']);
            Route::put('/profile', [AdminProfileController::class, 'update']);

            // Service Routes
            // Route::apiResource('services', AdminServiceController::class);

            // Reservation Routes
            Route::get('/reservations', [AdminReservationController::class, 'index']);
            Route::delete('/reservations/{reservation}', [AdminReservationController::class, 'destroy']);

            // Notification Routes
            Route::post('/notifications', [AdminNotificationController::class, 'send']);
        });
    });
});

// Example API routes
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});
