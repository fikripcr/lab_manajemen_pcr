<?php

use App\Http\Controllers\Hr\ApprovalController;
use App\Http\Controllers\Hr\AttDeviceController;
use App\Http\Controllers\Hr\DepartemenController;
use App\Http\Controllers\Hr\JabatanFungsionalController;
use App\Http\Controllers\Hr\JabatanStrukturalController;
use App\Http\Controllers\Hr\JenisShiftController;
use App\Http\Controllers\Hr\PegawaiController;
use App\Http\Controllers\Hr\PosisiController;
use App\Http\Controllers\Hr\StatusAktifitasController;
use App\Http\Controllers\Hr\StatusPegawaiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('hr')->name('hr.')->group(function () {

                                                                                          // Pegawai Routes
    Route::get('pegawai/data', [PegawaiController::class, 'data'])->name('pegawai.data'); // Ensure this exists if used, but PegawaiController uses index() for json.
    Route::resource('pegawai', PegawaiController::class);

    // Global Data Routes
    Route::get('keluarga/data', [\App\Http\Controllers\Hr\KeluargaController::class, 'data'])->name('keluarga.data');
    Route::get('pendidikan/data', [\App\Http\Controllers\Hr\RiwayatPendidikanController::class, 'data'])->name('pendidikan.data');

    // Nested Resources for Pegawai (History & Approval Workflow)
    Route::prefix('pegawai/{pegawai}')->name('pegawai.')->group(function () {
        // Multi-Value Lists (Add New)
        Route::resource('keluarga', \App\Http\Controllers\Hr\KeluargaController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('pendidikan', \App\Http\Controllers\Hr\RiwayatPendidikanController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('pengembangan', \App\Http\Controllers\Hr\PengembanganDiriController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Single-Value State Changes (Request Change)
        // Status Pegawai
        Route::get('status-pegawai/change', [\App\Http\Controllers\Hr\RiwayatStatPegawaiController::class, 'create'])->name('status-pegawai.create');
        Route::post('status-pegawai/change', [\App\Http\Controllers\Hr\RiwayatStatPegawaiController::class, 'store'])->name('status-pegawai.store');

        // Status Aktifitas
        Route::get('status-aktifitas/change', [\App\Http\Controllers\Hr\RiwayatStatAktifitasController::class, 'create'])->name('status-aktifitas.create');
        Route::post('status-aktifitas/change', [\App\Http\Controllers\Hr\RiwayatStatAktifitasController::class, 'store'])->name('status-aktifitas.store');

        // Jabatan Fungsional
        Route::get('jabatan-fungsional/change', [\App\Http\Controllers\Hr\RiwayatJabFungsionalController::class, 'create'])->name('jabatan-fungsional.create');
        Route::post('jabatan-fungsional/change', [\App\Http\Controllers\Hr\RiwayatJabFungsionalController::class, 'store'])->name('jabatan-fungsional.store');

        // Jabatan Struktural
        Route::get('jabatan-struktural/change', [\App\Http\Controllers\Hr\RiwayatJabStrukturalController::class, 'create'])->name('jabatan-struktural.create');
        Route::post('jabatan-struktural/change', [\App\Http\Controllers\Hr\RiwayatJabStrukturalController::class, 'store'])->name('jabatan-struktural.store');
    });

    // Departemen Routes
    Route::get('departemen/data', [DepartemenController::class, 'data'])->name('departemen.data');
    Route::resource('departemen', DepartemenController::class);

    // Posisi Routes
    Route::get('posisi/data', [PosisiController::class, 'data'])->name('posisi.data');
    Route::resource('posisi', PosisiController::class);

    // Tanggal Tidak Masuk (Holidays)
    Route::resource('tanggal-tidak-masuk', \App\Http\Controllers\Hr\TanggalTidakMasukController::class);

    // Fallback or Dashboard for HR?
    Route::get('/', function () {
        return redirect()->route('hr.pegawai.index');
    })->name('index');

    // Jabatan Fungsional
    Route::get('jabatan-fungsional/data', [JabatanFungsionalController::class, 'data'])->name('jabatan-fungsional.data');
    Route::resource('jabatan-fungsional', JabatanFungsionalController::class);

    // Jabatan Struktural
    Route::get('jabatan-struktural/data', [JabatanStrukturalController::class, 'data'])->name('jabatan-struktural.data');
    Route::resource('jabatan-struktural', JabatanStrukturalController::class);

    // Status Aktifitas
    Route::get('status-aktifitas/data', [StatusAktifitasController::class, 'data'])->name('status-aktifitas.data');
    Route::resource('status-aktifitas', StatusAktifitasController::class);

    // Status Pegawai
    Route::get('status-pegawai/data', [StatusPegawaiController::class, 'data'])->name('status-pegawai.data');
    Route::resource('status-pegawai', StatusPegawaiController::class);

    // Jenis Shift
    Route::get('jenis-shift/data', [JenisShiftController::class, 'data'])->name('jenis-shift.data');
    Route::resource('jenis-shift', JenisShiftController::class);

    // Mesin Presensi (AttDevice)
    Route::get('att-device/data', [AttDeviceController::class, 'data'])->name('att-device.data');
    Route::resource('att-device', AttDeviceController::class);

    // Approval Management
    Route::get('approval', [ApprovalController::class, 'index'])->name('approval.index');
    Route::post('approval/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('approval/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
});
