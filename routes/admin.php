<?php

use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\PengumumanController;;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\SoftwareRequestController;
use App\Http\Controllers\Admin\UserController;use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 Admin Routes (Auth Required)
// ==========================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    // ======================
    // 👤 Profile
    // ======================
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::patch('/password', 'passwordUpdate')->name('password.update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // ======================
    // 📦 Master Data
    // ======================

    // Users
    Route::get('api/users', [UserController::class, 'data'])->name('users.data');
    Route::resource('users', UserController::class);

    // Labs
    Route::get('api/labs', [LabController::class, 'data'])->name('labs.data');
    Route::resource('labs', LabController::class);

    // Inventories
    Route::prefix('inventories')->name('inventories.')->group(function () {
        Route::get('export', [InventarisController::class, 'export'])->name('export');
        Route::get('api', [InventarisController::class, 'data'])->name('data');
    });
    Route::resource('inventories', InventarisController::class);

    // Roles & Permissions
    // Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
    //     ->name('roles.update-permissions');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // Semester
    Route::get('api/semesters', [SemesterController::class, 'data'])->name('semesters.data');
    Route::resource('semesters', SemesterController::class);

    // Jadwal
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('import/form', [JadwalController::class, 'showImport'])->name('import.form');
        Route::post('import', [JadwalController::class, 'import'])->name('import');
        Route::get('api', [JadwalController::class, 'data'])->name('data');
    });
    Route::resource('jadwal', JadwalController::class);

    // Mata Kuliah
    Route::get('api/mata-kuliah', [MataKuliahController::class, 'data'])->name('mata-kuliah.data');
    Route::resource('mata-kuliah', MataKuliahController::class);

    // Software Requests
    Route::prefix('software-requests')->name('software-requests.')->controller(SoftwareRequestController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
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
        Route::get('/api/data', 'data')->name('data');
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
        Route::get('/api/data', 'data')->name('data');
    });
});
