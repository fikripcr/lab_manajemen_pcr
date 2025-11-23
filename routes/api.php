<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

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

// For in-app API calls, we can use session authentication
Route::middleware(['auth'])->group(function () {
    // Notification API routes
    Route::prefix('notifications')->name('api.notifications.')->group(function () {
        Route::get('/count', [NotificationController::class, 'getUnreadCount'])->name('count');
        Route::get('/list', [NotificationController::class, 'getList'])->name('list');
    });
});