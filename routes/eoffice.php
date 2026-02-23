<?php

use App\Http\Controllers\Eoffice\DashboardController;
use App\Http\Controllers\Eoffice\FeedbackController;
use App\Http\Controllers\Eoffice\JenisLayananController;
use App\Http\Controllers\Eoffice\JenisLayananDisposisiController;
use App\Http\Controllers\Eoffice\JenisLayananPeriodeController;
use App\Http\Controllers\Eoffice\KategoriIsianController;
use App\Http\Controllers\Eoffice\LayananController;
use App\Http\Controllers\Eoffice\LayananDiskusiController;
use App\Http\Controllers\Eoffice\LayananStatusController;
use App\Http\Controllers\Eoffice\MasterDataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.expired'])->group(function () {
    Route::prefix('eoffice')->name('eoffice.')->group(function () {

        // 🔹 Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');

        // 🔹 Master Data (Unified)
        Route::prefix('master-data')->name('master-data.')->group(function () {
            Route::get('/', [MasterDataController::class, 'index'])->name('index');
        });

        // Redirect root /eoffice to /eoffice/dashboard
        Route::get('/', function () {
            return redirect()->route('eoffice.dashboard');
        });

        // 🔹 Jenis Layanan Master
        Route::prefix('jenis-layanan')->name('jenis-layanan.')->group(function () {
            Route::get('/', [JenisLayananController::class, 'index'])->name('index');
            Route::get('/data', [JenisLayananController::class, 'paginate'])->name('data');
            Route::get('/create', [JenisLayananController::class, 'create'])->name('create');
            Route::post('/', [JenisLayananController::class, 'store'])->name('store');
            Route::get('/{jenis_layanan}', [JenisLayananController::class, 'show'])->name('show');
            Route::get('/{jenis_layanan}/edit', [JenisLayananController::class, 'edit'])->name('edit');
            Route::put('/{jenis_layanan}', [JenisLayananController::class, 'update'])->name('update');
            Route::delete('/{jenis_layanan}', [JenisLayananController::class, 'destroy'])->name('destroy');

            // PIC & Isian
            Route::post('/{jenis_layanan}/pic', [JenisLayananController::class, 'storePic'])->name('store-pic');
            Route::delete('/pic/{pic}', [JenisLayananController::class, 'destroyPic'])->name('destroy-pic');
            Route::post('/{jenis_layanan}/isian', [JenisLayananController::class, 'storeIsian'])->name('store-isian');
            Route::post('/isian/{isian}/toggle', [JenisLayananController::class, 'updateIsianField'])->name('update-isian-field');
            Route::post('/isian/{isian}/rule', [JenisLayananController::class, 'updateIsianRule'])->name('update-isian-rule');
            Route::post('/isian/{isian}/info', [JenisLayananController::class, 'updateIsianInfo'])->name('update-isian-info');
            Route::post('/isian/seq', [JenisLayananController::class, 'updateIsianSeq'])->name('update-isian-seq');
            Route::delete('/isian/{isian}', [JenisLayananController::class, 'destroyIsian'])->name('destroy-isian');

            // 🔹 Disposisi Chain
            Route::prefix('{jenis_layanan}/disposisi')->name('disposisi.')->group(function () {
                Route::get('/data', [JenisLayananDisposisiController::class, 'data'])->name('data');
                Route::get('/{disposisi}/data', [JenisLayananDisposisiController::class, 'show'])->name('get-data');
                Route::post('/', [JenisLayananDisposisiController::class, 'store'])->name('store');
                Route::post('/{disposisi}/seq', [JenisLayananDisposisiController::class, 'updateSeq'])->name('update-seq');
                Route::put('/{disposisi}/{action?}', [JenisLayananDisposisiController::class, 'update'])->name('update');
                Route::delete('/{disposisi}', [JenisLayananDisposisiController::class, 'destroy'])->name('destroy');
            });

            // 🔹 Periode
            Route::prefix('{jenis_layanan}/periode')->name('periode.')->group(function () {
                Route::get('/data', [JenisLayananPeriodeController::class, 'data'])->name('data');
                Route::get('/{periode}', [JenisLayananPeriodeController::class, 'show'])->name('show');
                Route::post('/', [JenisLayananPeriodeController::class, 'store'])->name('store');
                Route::put('/{periode}', [JenisLayananPeriodeController::class, 'update'])->name('update');
                Route::delete('/{periode}', [JenisLayananPeriodeController::class, 'destroy'])->name('destroy');
            });
        });

        // 🔹 Kategori Isian Master
        Route::prefix('kategori-isian')->name('kategori-isian.')->group(function () {
            Route::get('/', [KategoriIsianController::class, 'index'])->name('index');
            Route::get('/data', [KategoriIsianController::class, 'paginate'])->name('data');
            Route::get('/create', [KategoriIsianController::class, 'create'])->name('create');
            Route::post('/', [KategoriIsianController::class, 'store'])->name('store');
            Route::get('/{kategori_isian}/edit', [KategoriIsianController::class, 'edit'])->name('edit');
            Route::put('/{kategori_isian}', [KategoriIsianController::class, 'update'])->name('update');
            Route::delete('/{kategori_isian}', [KategoriIsianController::class, 'destroy'])->name('destroy');
        });

        // 🔹 Feedback
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/', [FeedbackController::class, 'index'])->name('index');
            Route::get('/data', [FeedbackController::class, 'data'])->name('data');
            Route::post('/', [FeedbackController::class, 'store'])->name('store');
        });

        // --- Layanan (Transactions) ---
        Route::prefix('layanan')->name('layanan.')->group(function () {
            Route::get('/', [LayananController::class, 'index'])->name('index');
            Route::get('/data', [LayananController::class, 'data'])->name('data');
            Route::get('/pilih', [LayananController::class, 'services'])->name('services');
            Route::get('/buat/{jenis_layanan}', [LayananController::class, 'create'])->name('create');
            Route::post('/simpan', [LayananController::class, 'store'])->name('store');
            Route::get('/{layanan}', [LayananController::class, 'show'])->name('show');
            Route::get('/{layanan}/download-pdf', [LayananController::class, 'downloadPdf'])->name('download-pdf');

            // 🔹 Status Update (dedicated controller)
            Route::post('/{layanan}/status/{status?}', [LayananStatusController::class, 'update'])->name('update-status');

            // 🔹 Discussion
            Route::post('/diskusi', [LayananDiskusiController::class, 'store'])->name('diskusi.store');
        });
    });
});
