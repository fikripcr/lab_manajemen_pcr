<?php

use App\Http\Controllers\Shared\DashboardController;
use App\Http\Controllers\Shared\MahasiswaController;
use App\Http\Controllers\Shared\PegawaiController;
use App\Http\Controllers\Shared\PengumumanController;
use App\Http\Controllers\Shared\PersonilController;
use App\Http\Controllers\Shared\StrukturOrganisasiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.expired'])->group(function () {

    // General Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shared Data Modules
    Route::prefix('shared')->name('shared.')->group(function () {

        // Pegawai
        Route::get('/pegawai/data', [PegawaiController::class, 'data'])->name('pegawai.data');
        Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');

        // Mahasiswa
        Route::get('/mahasiswa/data', [MahasiswaController::class, 'data'])->name('mahasiswa.data');
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');

        // Pengumuman
        Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
        Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');

        // Personil (Unified)
        Route::get('/personil/data', [PersonilController::class, 'data'])->name('personil.data');
        Route::get('/personil/{personil}/edit-modal', [PersonilController::class, 'editModal'])->name('personil.edit-modal.show');
        Route::post('/personil/{personil}/generate-user', [PersonilController::class, 'generateUser'])->name('personil.generate-user');
        Route::resource('personil', PersonilController::class);

        // Slideshow
        Route::post('/slideshow/reorder', [\App\Http\Controllers\Shared\SlideshowController::class, 'reorder'])->name('slideshow.reorder');
        Route::get('/slideshow/data', [\App\Http\Controllers\Shared\SlideshowController::class, 'data'])->name('slideshow.data');
        Route::resource('slideshow', \App\Http\Controllers\Shared\SlideshowController::class);

        // FAQ
        Route::post('/faq/reorder', [\App\Http\Controllers\Shared\FAQController::class, 'reorder'])->name('faq.reorder');
        Route::get('/faq/data', [\App\Http\Controllers\Shared\FAQController::class, 'data'])->name('faq.data');
        Route::resource('faq', \App\Http\Controllers\Shared\FAQController::class);

        // Struktur Organisasi
        Route::get('/struktur-organisasi/data', [StrukturOrganisasiController::class, 'data'])->name('struktur-organisasi.data');
        Route::post('/struktur-organisasi/reorder', [StrukturOrganisasiController::class, 'reorder'])->name('struktur-organisasi.reorder');
        Route::post('/struktur-organisasi/{id}/toggle-status', [StrukturOrganisasiController::class, 'toggleStatus'])->name('struktur-organisasi.toggle-status');
        Route::post('/struktur-organisasi/{id}/set-auditee', [StrukturOrganisasiController::class, 'setAuditee'])->name('struktur-organisasi.set-auditee');
        Route::resource('struktur-organisasi', StrukturOrganisasiController::class);

        // Info Publik CMS
        // Public Menu
        Route::post('/public-menu/reorder', [\App\Http\Controllers\Shared\PublicMenuController::class, 'reorder'])->name('public-menu.reorder');
        Route::resource('public-menu', \App\Http\Controllers\Shared\PublicMenuController::class);

        // Public Page
        Route::get('/public-page/data', [\App\Http\Controllers\Shared\PublicPageController::class, 'data'])->name('public-page.data');
        Route::resource('public-page', \App\Http\Controllers\Shared\PublicPageController::class);

    });

});
