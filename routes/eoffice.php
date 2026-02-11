<?php

use App\Http\Controllers\Eoffice\FeedbackController;
use App\Http\Controllers\Eoffice\JenisLayananController;
use App\Http\Controllers\Eoffice\JenisLayananDisposisiController;
use App\Http\Controllers\Eoffice\JenisLayananPeriodeController;
use App\Http\Controllers\Eoffice\KategoriIsianController;
use App\Http\Controllers\Eoffice\KategoriPerusahaanController;
use App\Http\Controllers\Eoffice\LayananController;
use App\Http\Controllers\Eoffice\LayananDiskusiController;
use App\Http\Controllers\Eoffice\LayananStatusController;
use App\Http\Controllers\Eoffice\PerusahaanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.expired'])->group(function () {
    Route::prefix('eoffice')->name('eoffice.')->group(function () {

        // 🔹 Jenis Layanan Master
        Route::prefix('jenis-layanan')->name('jenis-layanan.')->group(function () {
            Route::get('/', [JenisLayananController::class, 'index'])->name('index');
            Route::get('/data', [JenisLayananController::class, 'paginate'])->name('data');
            Route::get('/create', [JenisLayananController::class, 'create'])->name('create');
            Route::post('/', [JenisLayananController::class, 'store'])->name('store');
            Route::get('/{id}', [JenisLayananController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [JenisLayananController::class, 'edit'])->name('edit');
            Route::put('/{id}', [JenisLayananController::class, 'update'])->name('update');
            Route::delete('/{id}', [JenisLayananController::class, 'destroy'])->name('destroy');

            // PIC & Isian
            Route::post('/{id}/pic', [JenisLayananController::class, 'storePic'])->name('store-pic');
            Route::delete('/pic/{id}', [JenisLayananController::class, 'destroyPic'])->name('destroy-pic');
            Route::post('/{id}/isian', [JenisLayananController::class, 'storeIsian'])->name('store-isian');
            Route::post('/isian/{id}/toggle', [JenisLayananController::class, 'updateIsianField'])->name('update-isian-field');
            Route::post('/isian/{id}/rule', [JenisLayananController::class, 'updateIsianRule'])->name('update-isian-rule');
            Route::post('/isian/{id}/info', [JenisLayananController::class, 'updateIsianInfo'])->name('update-isian-info');
            Route::post('/isian/{id}/seq', [JenisLayananController::class, 'updateIsianSeq'])->name('update-isian-seq');
            Route::delete('/isian/{id}', [JenisLayananController::class, 'destroyIsian'])->name('destroy-isian');

            // 🔹 Disposisi Chain
            Route::prefix('{jenislayananId}/disposisi')->name('disposisi.')->group(function () {
                Route::get('/data', [JenisLayananDisposisiController::class, 'data'])->name('data');
                Route::get('/{id}/data', [JenisLayananDisposisiController::class, 'show'])->name('get-data');
                Route::post('/', [JenisLayananDisposisiController::class, 'store'])->name('store');
                Route::post('/{id}/seq', [JenisLayananDisposisiController::class, 'updateSeq'])->name('update-seq');
                Route::put('/{id}/{action?}', [JenisLayananDisposisiController::class, 'update'])->name('update');
                Route::delete('/{id}', [JenisLayananDisposisiController::class, 'destroy'])->name('destroy');
            });

            // 🔹 Periode
            Route::prefix('{jenislayananId}/periode')->name('periode.')->group(function () {
                Route::get('/data', [JenisLayananPeriodeController::class, 'data'])->name('data');
                Route::get('/{id}', [JenisLayananPeriodeController::class, 'show'])->name('show');
                Route::post('/', [JenisLayananPeriodeController::class, 'store'])->name('store');
                Route::put('/{id}', [JenisLayananPeriodeController::class, 'update'])->name('update');
                Route::delete('/{id}', [JenisLayananPeriodeController::class, 'destroy'])->name('destroy');
            });
        });

        // 🔹 Kategori Isian Master
        Route::prefix('kategori-isian')->name('kategori-isian.')->group(function () {
            Route::get('/', [KategoriIsianController::class, 'index'])->name('index');
            Route::get('/data', [KategoriIsianController::class, 'paginate'])->name('data');
            Route::get('/create', [KategoriIsianController::class, 'create'])->name('create');
            Route::post('/', [KategoriIsianController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [KategoriIsianController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KategoriIsianController::class, 'update'])->name('update');
            Route::delete('/{id}', [KategoriIsianController::class, 'destroy'])->name('destroy');
        });

        // 🔹 Kategori Perusahaan Master
        Route::prefix('kategori-perusahaan')->name('kategori-perusahaan.')->group(function () {
            Route::get('/', [KategoriPerusahaanController::class, 'index'])->name('index');
            Route::get('/data', [KategoriPerusahaanController::class, 'paginate'])->name('data');
            Route::get('/create', [KategoriPerusahaanController::class, 'create'])->name('create');
            Route::post('/', [KategoriPerusahaanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [KategoriPerusahaanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KategoriPerusahaanController::class, 'update'])->name('update');
            Route::delete('/{id}', [KategoriPerusahaanController::class, 'destroy'])->name('destroy');
        });

        // 🔹 Perusahaan Master
        Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
            Route::get('/', [PerusahaanController::class, 'index'])->name('index');
            Route::get('/data', [PerusahaanController::class, 'paginate'])->name('data');
            Route::get('/create', [PerusahaanController::class, 'create'])->name('create');
            Route::post('/', [PerusahaanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PerusahaanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PerusahaanController::class, 'update'])->name('update');
            Route::delete('/{id}', [PerusahaanController::class, 'destroy'])->name('destroy');
            Route::get('/{id}', [PerusahaanController::class, 'show'])->name('show');
        });

        // 🔹 Feedback
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/', [FeedbackController::class, 'index'])->name('index');
            Route::get('/data', [FeedbackController::class, 'data'])->name('data');
            Route::post('/', [FeedbackController::class, 'store'])->name('store');
        });

    });

    // --- Layanan (Transactions) ---
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/', [LayananController::class, 'index'])->name('index');
        Route::get('/data', [LayananController::class, 'data'])->name('data');
        Route::get('/pilih', [LayananController::class, 'services'])->name('services');
        Route::get('/buat/{jenis_id}', [LayananController::class, 'create'])->name('create');
        Route::post('/simpan', [LayananController::class, 'store'])->name('store');
        Route::get('/{id}', [LayananController::class, 'show'])->name('show');
        Route::get('/{id}/download-pdf', [LayananController::class, 'downloadPdf'])->name('download-pdf');

        // 🔹 Status Update (dedicated controller)
        Route::post('/{id}/status/{status?}', [LayananStatusController::class, 'update'])->name('update-status');

        // 🔹 Discussion
        Route::post('/diskusi', [LayananDiskusiController::class, 'store'])->name('diskusi.store');
    });
});
