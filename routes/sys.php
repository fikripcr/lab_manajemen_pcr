<?php

use App\Http\Controllers\Sys\ActivityLogController;
use App\Http\Controllers\Sys\AppConfigController;
use App\Http\Controllers\Sys\BackupController;
use App\Http\Controllers\Sys\DashboardController;
use App\Http\Controllers\Sys\DocumentationController;
use App\Http\Controllers\Sys\ErrorLogController;
use App\Http\Controllers\Sys\SysGlobalSearchController;
use App\Http\Controllers\Sys\NotificationsController;
use App\Http\Controllers\Sys\PermissionController;
use App\Http\Controllers\Sys\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.expired'])->group(function () {
    // ==========================
    // 🔹 System Management Routes (require authentication)
    // All routes are prefixed with /sys/
    // ==========================
    Route::prefix('sys')->group(function () {
        // System Dashboard - accessible via /sys
        Route::get('/', [DashboardController::class, 'index'])->name('sys.dashboard');

        // Test Functions
        Route::post('/dashboard/test-email', [DashboardController::class, 'testEmail'])->name('sys.dashboard.test-email');
        Route::post('/dashboard/test-notification', [DashboardController::class, 'testNotification'])->name('sys.dashboard.test-notification');
        Route::post('/dashboard/test-pdf-export', [DashboardController::class, 'testPdfExport'])->name('sys.dashboard.test-pdf-export');

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

        // Send test notification to authenticated user
        Route::post('/send-test-notification', [NotificationsController::class, 'sendTestNotification'])->name('send.test.notification');

        // Send notification to specific user
        Route::post('/users/{user}/send-notification', [NotificationsController::class, 'sendToUser'])->name('users.send.notification');

        // Sys Global Search - accessible via /sys/sys-search
        Route::get('/sys-search', [SysGlobalSearchController::class, 'search'])->name('sys-search');

        // App Configuration - accessible via /sys/app-config
        Route::get('/app-config', [AppConfigController::class, 'index'])->name('app-config');
        Route::post('/app-config', [AppConfigController::class, 'update'])->name('app-config.update');
        Route::post('/app-config/clear-cache', [AppConfigController::class, 'clearCache'])->name('app-config.clear-cache');
        Route::post('/app-config/optimize', [AppConfigController::class, 'optimize'])->name('app-config.optimize');

        // Backup Management - accessible via /sys/backup
        Route::prefix('backup')->name('admin.backup.')->group(function () {
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

        // Roles & Permissions
        Route::resource('roles', RoleController::class);
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('api', [PermissionController::class, 'paginate'])->name('data');
            Route::get('create-modal', [PermissionController::class, 'createModal'])->name('create-modal');
            Route::get('edit-modal/{permissionId?}', [PermissionController::class, 'editModal'])->name('edit-modal.show');
        });
        Route::resource('permissions', PermissionController::class);

        // Testing Dashboard - accessible via /sys/test
        Route::prefix('test')->name('test.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Sys\TestController::class, 'index'])->name('index');
            Route::post('/email', [\App\Http\Controllers\Sys\TestController::class, 'testEmail'])->name('email');
            Route::post('/notification', [\App\Http\Controllers\Sys\TestController::class, 'testNotification'])->name('notification');
            Route::post('/pdf-export', [\App\Http\Controllers\Sys\TestController::class, 'testPdfExport'])->name('pdf-export');
        });

        // Documentation Routes - accessible via /sys/documentation
        Route::prefix('documentation')->name('sys.documentation.')->group(function () {
            Route::get('/', [DocumentationController::class, 'index'])->name('index');
            Route::get('/{page?}', [DocumentationController::class, 'show'])->name('show');
            Route::get('/edit/{page?}', [DocumentationController::class, 'edit'])->name('edit');
            Route::put('/update/{page?}', [DocumentationController::class, 'update'])->name('update');
        });
    });
});
