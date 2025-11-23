<?php

use Illuminate\Support\Facades\Route;
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



// For in-app API calls, we use session authentication (web middleware)
Route::middleware(['web','auth:sanctum'])->group(function () {
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
});
