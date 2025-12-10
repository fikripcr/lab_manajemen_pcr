<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Sys\AuthController;
use App\Http\Controllers\Api\Sys\SystemDataController;
use App\Http\Controllers\Api\Sys\ActivityLogController;
use App\Http\Controllers\Api\Sys\NotificationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Enjoy building your API!
|
*/

// Public API routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
});

// For in-app API calls, we use session authentication (web middleware)
Route::middleware(['web','auth:sanctum'])->group(function () {
    // Authentication routes
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('api.auth.me');

    // Notification API routes
    Route::prefix('notifications')->name('api.notifications.')->group(function () {
        Route::get('/count', [NotificationController::class, 'getCount'])->name('count');
        Route::get('/list', [NotificationController::class, 'getList'])->name('list');
    });

    // Activity Log API routes
    Route::prefix('activity-logs')->name('api.activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'list'])->name('list');
        Route::get('/{id}', [ActivityLogController::class, 'detail'])->name('detail');
    });

    // Permission API routes
    Route::prefix('permissions')->name('api.permissions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\Sys\PermissionController::class, 'index'])->name('index');
        Route::get('/search', [\App\Http\Controllers\Api\Sys\PermissionController::class, 'search'])->name('search');
    });
});
