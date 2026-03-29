<?php

use App\Http\Controllers\Lab\DashboardController;
use App\Http\Controllers\Lab\InventarisController;
use App\Http\Controllers\Lab\JadwalController;
use App\Http\Controllers\Lab\KegiatanController;
use App\Http\Controllers\Lab\LabController;
use App\Http\Controllers\Lab\LabInventarisController;
use App\Http\Controllers\Lab\LabTeamController;
use App\Http\Controllers\Lab\LaporanKerusakanController;
use App\Http\Controllers\Lab\LogPenggunaanLabController;
use App\Http\Controllers\Lab\LogPenggunaanPcController;
use App\Http\Controllers\Lab\PcAssignmentController;
use App\Http\Controllers\Lab\PeriodSoftRequestController;
use App\Http\Controllers\Lab\SoftwareRequestController;
use App\Http\Controllers\Lab\SuratBebasLabController;
use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 Lab Routes (former Admin)
// ==========================
Route::prefix('lab')->name('lab.')->middleware(['auth', 'check.expired'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    // Users
    // User routes moved to sys.php

    // Labs
    Route::get('api/labs', [LabController::class, 'data'])->name('labs.data');
    Route::prefix('labs/{lab_id}')->name('labs.')->group(function () {
        Route::get('inventaris/data', [LabInventarisController::class, 'data'])->name('inventaris.data');
        Route::resource('inventaris', LabInventarisController::class);
        Route::get('inventaris/get-inventaris', [LabInventarisController::class, 'getInventaris'])->name('inventaris.get-inventaris');
        Route::get('teams/get-users', [LabTeamController::class, 'getUsers'])->name('teams.get-users');
        Route::resource('teams', LabTeamController::class);
    });

    Route::resource('labs', LabController::class);

    // Inventaris
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('export', [InventarisController::class, 'export'])->name('export');
        Route::get('data', [InventarisController::class, 'data'])->name('data');
    });
    Route::resource('inventaris', InventarisController::class);

    // Jadwal
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('import/form', [JadwalController::class, 'showImport'])->name('import.form');
        Route::post('import', [JadwalController::class, 'import'])->name('import.store');
        Route::get('data', [JadwalController::class, 'data'])->name('data');

        // PC Assignments Nested Routes
        Route::prefix('{jadwal}/assignments')->name('assignments.')->controller(PcAssignmentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::delete('/{assignment}', 'destroy')->name('destroy');
        });
    });
    Route::resource('jadwal', JadwalController::class);

    // Software Requests
    Route::prefix('software-requests')->name('software-requests.')->controller(SoftwareRequestController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::post('/{id}/approve', 'approve')->name('approve');
    });

    // Log Penggunaan PC
    Route::prefix('log-pc')->name('log-pc.')->controller(LogPenggunaanPcController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
    });

    // Laporan Kerusakan
    Route::prefix('laporan-kerusakan')->name('laporan-kerusakan.')->controller(LaporanKerusakanController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/ajax/inventaris', 'getInventaris')->name('inventaris');
    });

    // Peminjaman Lab (Kegiatan)
    Route::prefix('kegiatan')->name('kegiatan.')->controller(KegiatanController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/status', 'updateStatus')->name('status');
    });

    // Log Penggunaan Lab (Guest/Event)
    Route::prefix('log-lab')->name('log-lab.')->controller(LogPenggunaanLabController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
    });

    // Surat Bebas Lab
    Route::prefix('surat-bebas')->name('surat-bebas.')->controller(SuratBebasLabController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/status', 'updateStatus')->name('status');
    });

    // Period Requests
    Route::get('api/periode-request', [PeriodSoftRequestController::class, 'data'])->name('periode-request.data');
    Route::resource('periode-request', PeriodSoftRequestController::class);

});
