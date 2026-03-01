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
use App\Http\Controllers\Lab\MahasiswaController;
use App\Http\Controllers\Lab\MataKuliahController;
use App\Http\Controllers\Lab\PcAssignmentController;
use App\Http\Controllers\Lab\PengumumanController;
use App\Http\Controllers\Lab\PeriodSoftRequestController;
use App\Http\Controllers\Lab\PersonilController;
use App\Http\Controllers\Lab\SemesterController;
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

    // Semester
    Route::prefix('semesters')->name('semesters.')->group(function () {
        Route::get('data', [SemesterController::class, 'data'])->name('data');
        Route::get('create-modal', [SemesterController::class, 'createModal'])->name('create-modal');
        Route::get('edit-modal/{semesterid?}', [SemesterController::class, 'editModal'])->name('edit-modal.show');
    });
    Route::resource('semesters', SemesterController::class);

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

    // Mata Kuliah
    Route::get('api/mata-kuliah', [MataKuliahController::class, 'data'])->name('mata-kuliah.data');
    Route::resource('mata-kuliah', MataKuliahController::class);

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
        Route::get('/{pengumuman}', 'show')->name('show');
        Route::get('/{pengumuman}/edit', 'edit')->name('edit');
        Route::put('/{pengumuman}', 'update')->name('update');
        Route::delete('/{pengumuman}', 'destroy')->name('destroy');
        Route::get('/api/data', 'data')->name('data');
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

// Mahasiswa
    Route::get('api/mahasiswa', [MahasiswaController::class, 'data'])->name('mahasiswa.data');
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('edit-modal/{id}', [MahasiswaController::class, 'editModal'])->name('edit-modal.show');
        Route::post('{mahasiswa}/generate-user', [MahasiswaController::class, 'generateUser'])->name('generate-user');
    });
    Route::resource('mahasiswa', MahasiswaController::class);

// Personil
    Route::get('api/personil', [PersonilController::class, 'data'])->name('personil.data');
    Route::prefix('personil')->name('personil.')->group(function () {
        Route::get('edit-modal/{id}', [PersonilController::class, 'editModal'])->name('edit-modal.show');
    });
    Route::resource('personil', PersonilController::class);

});
