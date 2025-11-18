<?php

use App\Http\Controllers\Sys\ActivityLogController;
use App\Http\Controllers\Sys\AppConfigController;
use App\Http\Controllers\Sys\BackupController;
use App\Http\Controllers\Sys\DocumentationController;
use App\Http\Controllers\Sys\GlobalSearchController;
use App\Http\Controllers\Sys\NotificationsController;
use App\Http\Controllers\Sys\PermissionController;
use App\Http\Controllers\Sys\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
// ==========================
// 🔹 System Management Routes (require authentication)
// ==========================

// Activity Log Routes
    Route::prefix('activity-log')->name('activity-log.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/data', [ActivityLogController::class, 'paginate'])->name('data');
        Route::get('/{id}', [ActivityLogController::class, 'show'])->name('show');
    });

// Notifications Management
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationsController::class, 'index'])->name('index');
        Route::get('/data', [NotificationsController::class, 'paginate'])->name('data');
        Route::get('/dropdown-data', [NotificationsController::class, 'getDropdownData'])->name('dropdown-data');
        Route::get('/mark-as-read/{id}', [NotificationsController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::post('/mark-selected-as-read', [NotificationsController::class, 'markSelectedAsRead'])->name('mark-selected-as-read');
        Route::get('/unread-count', [NotificationsController::class, 'getUnreadCount'])->name('unread-count');
    });

// Send test notification to authenticated user
    Route::post('/send-test-notification', [NotificationsController::class, 'sendTestNotification'])->name('send.test.notification');

// Send notification to specific user
    Route::post('/users/{user}/send-notification', [NotificationsController::class, 'sendToUser'])->name('users.send.notification');

// Global Search
    Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');

// App Configuration
    Route::get('/app-config', [AppConfigController::class, 'index'])->name('app-config');
    Route::post('/app-config', [AppConfigController::class, 'update'])->name('app-config.update');
    Route::post('/app-config/clear-cache', [AppConfigController::class, 'clearCache'])->name('app-config.clear-cache');
    Route::post('/app-config/optimize', [AppConfigController::class, 'optimize'])->name('app-config.optimize');

// Backup Management
    Route::prefix('backup')->name('admin.backup.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/create', [BackupController::class, 'create'])->name('create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->where('filename', '.*')->name('download');
        Route::delete('/delete/{filename}', [BackupController::class, 'delete'])->where('filename', '.*')->name('delete');
    });

// Roles & Permissions
    Route::resource('roles', RoleController::class);
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('api', [PermissionController::class, 'paginate'])->name('data');
        Route::get('create-modal', [PermissionController::class, 'createModal'])->name('create-modal');
        Route::get('edit-modal/{permissionId?}', [PermissionController::class, 'editModal'])->name('edit-modal.show');
    });
    Route::resource('permissions', PermissionController::class);

// ==========================
// 🔹 Documentation Route
// ==========================
    Route::get('/documentation', [DocumentationController::class, 'index'])->name('admin.documentation');

});
