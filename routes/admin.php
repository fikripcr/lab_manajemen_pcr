<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LabController;

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LabTeamController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserPdfController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\PengumumanController;;
use App\Http\Controllers\Admin\DocumentationController;
use App\Http\Controllers\Admin\LabInventarisController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\SoftwareRequestController;

// ==========================
// 🔹 Admin Routes (Auth Required)
// ==========================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('api', [UserController::class, 'paginate'])->name('data');
        Route::get('export', [UserController::class, 'export'])->name('export');
        Route::get('export-pdf', [UserController::class, 'exportPdf'])->name('export.pdf');
        Route::get('export-pdf/{id}', [UserController::class, 'exportPdf'])->name('export.pdf.detail');
        Route::get('import', [UserController::class, 'showImport'])->name('import.show');
        Route::post('import', [UserController::class, 'import'])->name('import.store');
    });
    Route::resource('users', UserController::class);

    // Labs
    Route::get('api/labs', [LabController::class, 'paginate'])->name('labs.data');
    Route::prefix('labs/{lab_id}')->name('labs.')->group(function () {
        Route::resource('inventaris', LabInventarisController::class);
        Route::get('teams/get-users', [LabTeamController::class, 'getUsers'])->name('teams.get-users');
        Route::resource('teams', LabTeamController::class);
    });

    Route::resource('labs', LabController::class);

    // Inventories
    Route::prefix('inventories')->name('inventories.')->group(function () {
        Route::get('export', [InventarisController::class, 'export'])->name('export');
        Route::get('api', [InventarisController::class, 'paginate'])->name('data');
    });
    Route::resource('inventories', InventarisController::class);

    // Roles & Permissions
    Route::resource('roles', RoleController::class);
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('api', [PermissionController::class, 'paginate'])->name('data');
        Route::get('create-modal', [PermissionController::class, 'createModal'])->name('create-modal');
        Route::get('edit-modal/{permissionId?}', [PermissionController::class, 'editModal'])->name('edit-modal.show');
    });
    Route::resource('permissions', PermissionController::class);

    // Semester
    Route::prefix('semesters')->name('semesters.')->group(function () {
        Route::get('api/semesters', [SemesterController::class, 'paginate'])->name('data');
        Route::get('create-modal', [SemesterController::class, 'createModal'])->name('create-modal');
        Route::get('edit-modal/{semesterid?}', [SemesterController::class, 'editModal'])->name('edit-modal.show');
    });
    Route::resource('semesters', SemesterController::class);

    // Jadwal
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('import/form', [JadwalController::class, 'showImport'])->name('import.form');
        Route::post('import', [JadwalController::class, 'import'])->name('import.store');
        Route::get('api', [JadwalController::class, 'paginate'])->name('data');
    });
    Route::resource('jadwal', JadwalController::class);

    // Mata Kuliah
    Route::get('api/mata-kuliah', [MataKuliahController::class, 'paginate'])->name('mata-kuliah.data');
    Route::resource('mata-kuliah', MataKuliahController::class);

    // Software Requests
    Route::prefix('software-requests')->name('software-requests.')->controller(SoftwareRequestController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'paginate')->name('data');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
    });

    // Pengumuman
    Route::prefix('pengumuman')->name('pengumuman.')->controller(PengumumanController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{pengumuman}', 'show')->name('show');
        Route::get('/{pengumuman}/edit', 'edit')->name('edit');
        Route::put('/{pengumuman}', 'update')->name('update');
        Route::delete('/{pengumuman}', 'destroy')->name('destroy');
        Route::get('/api/data', 'paginate')->name('data');
    });

    // Berita
    Route::prefix('berita')->name('berita.')->controller(PengumumanController::class)->group(function () {
        Route::get('/', 'beritaIndex')->name('index');
        Route::get('/create', 'create')->defaults('type', 'berita')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{berita}', 'show')->name('show');
        Route::get('/{berita}/edit', 'edit')->name('edit');
        Route::put('/{berita}', 'update')->name('update');
        Route::delete('/{berita}', 'destroy')->name('destroy');
        Route::get('/api/data', 'paginate')->name('data');
    });


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

    // Login as specific user
    Route::post('/users/{user}/login-as', [UserController::class, 'loginAs'])->name('users.login.as');

    // Switch back to original user
    Route::get('/switch-back', [UserController::class, 'switchBack'])->name('admin.switch-back');
});

// Laravel Impersonate Routes
Route::impersonate();

// ==========================
// 🔹 Documentation Route
// ==========================
Route::get('/documentation', [DocumentationController::class, 'index'])->name('admin.documentation');


