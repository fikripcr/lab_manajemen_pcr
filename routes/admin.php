<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\LabInventarisController;
use App\Http\Controllers\Admin\LabTeamController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\SoftwareRequestController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

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
        Route::get('inventaris/get-inventaris', [LabInventarisController::class, 'getInventaris'])->name('inventaris.get-inventaris');
        Route::get('teams/get-users', [LabTeamController::class, 'getUsers'])->name('teams.get-users');
        Route::resource('teams', LabTeamController::class);
    });

    Route::resource('labs', LabController::class);

    // Inventaris
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('export', [InventarisController::class, 'export'])->name('export');
        Route::get('api', [InventarisController::class, 'paginate'])->name('data');
    });
    Route::resource('inventaris', InventarisController::class);

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

});
