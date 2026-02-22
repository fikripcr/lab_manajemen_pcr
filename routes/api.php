<?php

use App\Http\Controllers\Api\Sys\ActivityLogController;
use App\Http\Controllers\Api\Sys\AuthController;
use App\Http\Controllers\Api\Sys\NotificationController;
use App\Http\Controllers\Api\Sys\PermissionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware(['web', 'auth:sanctum'])->group(function () {
    // Authentication routes
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('api.auth.me');

    // Notification API routes
    Route::prefix('notifications')->name('api.notifications.')->group(function () {
        Route::get('/count', [NotificationController::class, 'getCount'])->name('count');
        Route::get('/list', [NotificationController::class, 'getList'])->name('list');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
    });

    // Activity Log API routes
    Route::prefix('activity-logs')->name('api.activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'list'])->name('list');
        Route::get('/{id}', [ActivityLogController::class, 'detail'])->name('detail');
    });

    // Permission API routes
    Route::prefix('permissions')->name('api.permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/search', [PermissionController::class, 'search'])->name('search');
    });

    // Users API routes
    Route::get('/users/search', function (Request $request) {
        $query = User::query();
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        return $query->select('id', 'name')->orderBy('name')->limit(50)->get();
    })->name('api.users.search');
});
