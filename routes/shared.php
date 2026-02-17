<?php

use App\Http\Controllers\Shared\DashboardController;
use App\Http\Controllers\Shared\MahasiswaController;
use App\Http\Controllers\Shared\PegawaiController;
use App\Http\Controllers\Shared\PengumumanController;
use App\Http\Controllers\Shared\PersonilController;
use App\Http\Controllers\Shared\StrukturOrganisasiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    // General Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shared Data Modules
    Route::prefix('shared')->name('shared.')->group(function () {

        // Pegawai
        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');

        // Mahasiswa
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');

        // Pengumuman
        Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
        Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');

        // Personil (Outsource)
        Route::get('/personil', [PersonilController::class, 'index'])->name('personil.index');
        Route::get('/personil/{id}', [PersonilController::class, 'show'])->name('personil.show');

        // Struktur Organisasi
        Route::post('/struktur-organisasi/reorder', [StrukturOrganisasiController::class, 'reorder'])->name('struktur-organisasi.reorder');
        Route::post('/struktur-organisasi/{id}/toggle-status', [StrukturOrganisasiController::class, 'toggleStatus'])->name('struktur-organisasi.toggle-status');
        Route::post('/struktur-organisasi/{id}/set-auditee', [StrukturOrganisasiController::class, 'setAuditee'])->name('struktur-organisasi.set-auditee');
        Route::resource('struktur-organisasi', StrukturOrganisasiController::class);

    });

});
