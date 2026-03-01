<?php

use App\Http\Controllers\Sys\ActivityLogController;
use App\Http\Controllers\Sys\AppConfigController;
use App\Http\Controllers\Sys\BackupController;
use App\Http\Controllers\Sys\DashboardController;
use App\Http\Controllers\Sys\DocumentationController;
use App\Http\Controllers\Sys\ErrorLogController;
use App\Http\Controllers\Sys\NotificationsController;
use App\Http\Controllers\Sys\PermissionController;
use App\Http\Controllers\Sys\RoleController;
use App\Http\Controllers\Sys\SysGlobalSearchController;
use App\Http\Controllers\Sys\TestController;
use App\Http\Controllers\Sys\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

// 🔹 Public System Routes (Accessible by Guest)

Route::middleware(['auth', 'check.expired'])->group(function () {
    // 🔹 Unified Profile Route (Accessible by any authenticated user)
    Route::get('/profile', [UserController::class, 'show'])->name('sys.profile');
    Route::get('/profile/edit', [UserController::class, 'editProfileAjax'])->name('sys.profile.edit');
    Route::put('/profile/update', [UserController::class, 'updateProfileAjax'])->name('sys.profile.update');

    // ==========================
    // 🔹 System Management Routes (require authentication)
    // All routes are prefixed with /sys/
    // ==========================
    Route::prefix('sys')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('sys.dashboard');

        // Users Management
        Route::prefix('users')->name('sys.users.')->group(function () {
            Route::get('api', [UserController::class, 'paginate'])->name('data');
            Route::get('export', [UserController::class, 'export'])->name('export');
            Route::get('export-pdf', [UserController::class, 'exportPdf'])->name('export.pdf');
            Route::get('export-pdf/{id}', [UserController::class, 'exportPdf'])->name('export.pdf.detail');
            Route::get('import', [UserController::class, 'showImport'])->name('import.show');
            Route::post('import', [UserController::class, 'import'])->name('import.store');
            Route::get('change-password', [UserController::class, 'changePassword'])->name('change-password');
        });
        Route::resource('users', UserController::class, ['as' => 'sys']);
        Route::post('/users/{user}/login-as', [UserController::class, 'loginAs'])->name('sys.users.login.as');
        Route::get('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('sys.users.reset-password');
        Route::put('/users/{user}/reset-password', [UserController::class, 'updatePassword'])->name('sys.users.update-password');
        Route::get('/switch-back', [UserController::class, 'switchBack'])->name('sys.users.switch-back');
        Route::post('/users/switch-role/{role?}', [UserController::class, 'switchRole'])->name('sys.users.switch-role');

        // Layout & Theme Settings Routes (via AppConfig)
        // Moved to public scope (see top of file) for Auth pages access

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
            Route::post('/', [BackupController::class, 'store'])->name('store');
            Route::get('/download/{filename}', [BackupController::class, 'download'])->where('filename', '.*')->name('download');
            Route::delete('/{filename}', [BackupController::class, 'destroy'])->where('filename', '.*')->name('destroy');
        });

        // Error Log Management - accessible via /sys/error-log
        Route::prefix('error-log')->name('sys.error-log.')->group(function () {
            Route::get('/', [ErrorLogController::class, 'index'])->name('index');
            Route::get('data', [ErrorLogController::class, 'paginate'])->name('data');
            Route::get('{id}', [ErrorLogController::class, 'show'])->name('show');
            Route::post('clear-all', [ErrorLogController::class, 'clearAll'])->name('clear-all');
            Route::get('test', [ErrorLogController::class, 'testErrorLog'])->name('test');
        });

        // Roles Management - accessible via /sys/roles
        Route::prefix('roles')->name('sys.roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/matrix', [RoleController::class, 'matrix'])->name('matrix');
            Route::post('/matrix', [RoleController::class, 'updateMatrix'])->name('update-matrix');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
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
            Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
            Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
        });

        // Testing Dashboard - accessible via /sys/test
        Route::prefix('test')->name('sys.test.')->group(function () {
            Route::get('/', [TestController::class, 'index'])->name('index');
            Route::post('/email', [TestController::class, 'testEmail'])->name('email');
            Route::post('/notification', [TestController::class, 'testNotification'])->name('notification');
            Route::post('/pdf-export', [TestController::class, 'testPdfExport'])->name('pdf-export');
            Route::post('/excel-export', [TestController::class, 'testExcelExport'])->name('excel-export');
            Route::post('/word-export', [TestController::class, 'testWordExport'])->name('word-export');
            Route::post('/generate-qrcode', [TestController::class, 'generateQrCode'])->name('generate-qrcode');
            Route::get('/qrcode/show', [TestController::class, 'showQrCode'])->name('qrcode.show');
            Route::get('/qrcode', [TestController::class, 'qrCode'])->name('qrcode');
            Route::get('/qrcode/download/{filename}', [TestController::class, 'downloadQrCode'])->name('qrcode.download');

            Route::get('/features', [TestController::class, 'features'])->name('features');

            // DOCX Template Routes
            Route::post('/docx-template', [TestController::class, 'testDocxTemplate'])->name('docx-template');
            Route::post('/upload-docx-template', [TestController::class, 'uploadDocxTemplate'])->name('upload-docx-template');
        });

        // Documentation Routes - accessible via /sys/documentation
        Route::prefix('documentation')->name('sys.documentation.')->group(function () {
            Route::get('/', [DocumentationController::class, 'index'])->name('index');
            Route::get('/{page?}', [DocumentationController::class, 'show'])->name('show');
            Route::get('/edit/{page?}', [DocumentationController::class, 'edit'])->name('edit');
            Route::put('/update/{page?}', [DocumentationController::class, 'update'])->name('update');
        });

        // Get current server time
        Route::get('/server-time', function () {
            return response()->json([
                'server_time' => Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm:ss'),
            ]);
        })->name('sys.server-time');
    });
});
