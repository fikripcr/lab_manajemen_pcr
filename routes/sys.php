<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sys\RoleController;
use App\Http\Controllers\Sys\TestController;
use App\Http\Controllers\Sys\BackupController;
use App\Http\Controllers\Sys\ErrorLogController;
use App\Http\Controllers\Sys\AppConfigController;
use App\Http\Controllers\Sys\DashboardController;
use App\Http\Controllers\Sys\PermissionController;
use App\Http\Controllers\Sys\ActivityLogController;
use App\Http\Controllers\Sys\DocumentationController;
use App\Http\Controllers\Sys\NotificationsController;
use App\Http\Controllers\Sys\SysGlobalSearchController;

Route::middleware(['auth', 'check.expired'])->group(function () {
    // ==========================
    // 🔹 System Management Routes (require authentication)
    // All routes are prefixed with /sys/
    // ==========================
    Route::prefix('sys')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('sys.dashboard');

        // Activity Log Routes - accessible via /sys/activity-log
        Route::prefix('activity-log')->name('activity-log.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/data', [ActivityLogController::class, 'paginate'])->name('data');
            Route::get('/{id?}', [ActivityLogController::class, 'show'])->name('show');
        });

        // Notifications Management - accessible via /sys/notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationsController::class, 'index'])->name('index');
            Route::get('/data', [NotificationsController::class, 'paginate'])->name('data');
            Route::get('/dropdown-data', [NotificationsController::class, 'getDropdownData'])->name('dropdown-data');
            Route::get('/mark-as-read/{id}', [NotificationsController::class, 'markAsRead'])->name('mark-as-read');
            Route::post('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('mark-all-as-read');
            Route::post('/mark-selected-as-read', [NotificationsController::class, 'markSelectedAsRead'])->name('mark-selected-as-read');
            Route::get('/unread-count', [NotificationsController::class, 'getUnreadCount'])->name('unread-count');
            Route::post('/test', [NotificationsController::class, 'testNotification'])->name('test');
            Route::get('/counts', [NotificationsController::class, 'getNotificationCounts'])->name('counts');
            Route::put('/update/{id?}', [NotificationsController::class, 'update'])->name('update');
            Route::post('/send', [NotificationsController::class, 'send'])->name('send');
        });

        // Sys Global Search - accessible via /sys/sys-search
        Route::get('/sys-search', [SysGlobalSearchController::class, 'search'])->name('sys-search');

        // App Configuration - accessible via /sys/app-config
        Route::get('/app-config', [AppConfigController::class, 'index'])->name('app-config');
        Route::post('/app-config', [AppConfigController::class, 'update'])->name('app-config.update');
        Route::post('/app-config/clear-cache', [AppConfigController::class, 'clearCache'])->name('app-config.clear-cache');
        Route::post('/app-config/optimize', [AppConfigController::class, 'optimize'])->name('app-config.optimize');

        // Backup Management - accessible via /sys/backup
        Route::prefix('backup')->name('sys.backup.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::post('/create', [BackupController::class, 'create'])->name('create');
            Route::get('/download/{filename}', [BackupController::class, 'download'])->where('filename', '.*')->name('download');
            Route::delete('/delete/{filename}', [BackupController::class, 'delete'])->where('filename', '.*')->name('delete');
        });

        // Error Log Management - accessible via /sys/error-log
        Route::prefix('error-log')->name('sys.error-log.')->group(function () {
            Route::get('/', [ErrorLogController::class, 'index'])->name('index');
            Route::get('data', [ErrorLogController::class, 'paginate'])->name('data');
            Route::get('{id}', [ErrorLogController::class, 'show'])->name('show');
            Route::delete('{id}', [ErrorLogController::class, 'destroy'])->name('destroy');
            Route::post('clear-all', [ErrorLogController::class, 'clearAll'])->name('clear-all');
            Route::get('test', [ErrorLogController::class, 'testErrorLog'])->name('test');
        });

        // Roles Management - accessible via /sys/roles
        Route::prefix('roles')->name('sys.roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
            Route::put('/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('update-permissions');
        });

        // Permissions Management - accessible via /sys/permissions
        Route::prefix('permissions')->name('sys.permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::get('/api', [PermissionController::class, 'paginate'])->name('data');
            Route::get('/create', [PermissionController::class, 'create'])->name('create');
            Route::post('/', [PermissionController::class, 'store'])->name('store');
            Route::get('/{permissionId}', [PermissionController::class, 'show'])->name('show');
            Route::get('/{permissionId}/edit', [PermissionController::class, 'edit'])->name('edit');
            Route::put('/{permissionId}', [PermissionController::class, 'update'])->name('update');
            Route::delete('/{permissionId}', [PermissionController::class, 'destroy'])->name('destroy');
            Route::get('/create-modal', [PermissionController::class, 'createModal'])->name('create-modal');
            Route::get('/edit-modal/{permissionId}', [PermissionController::class, 'editModal'])->name('edit-modal.show');
        });

        // Testing Dashboard - accessible via /sys/test
        Route::prefix('test')->name('sys.test.')->group(function () {
            Route::get('/', [TestController::class, 'index'])->name('index');
            Route::post('/email', [TestController::class, 'testEmail'])->name('email');
            Route::post('/notification', [TestController::class, 'testNotification'])->name('notification');
            Route::post('/pdf-export', [TestController::class, 'testPdfExport'])->name('pdf-export');
        });

        // Documentation Routes - accessible via /sys/documentation
        Route::prefix('documentation')->name('sys.documentation.')->group(function () {
            Route::get('/', [DocumentationController::class, 'index'])->name('index');
            Route::get('/{page?}', [DocumentationController::class, 'show'])->name('show');
            Route::get('/edit/{page?}', [DocumentationController::class, 'edit'])->name('edit');
            Route::put('/update/{page?}', [DocumentationController::class, 'update'])->name('update');
        });

        // Get current server time
        Route::get('/server-time', function() {
            return response()->json([
                'server_time' => \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm:ss')
            ]);
        })->name('sys.server-time');
    });
});
