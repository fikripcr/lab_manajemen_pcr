<?php

use App\Http\Controllers\Pmb\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('pmb')->name('pmb.')->group(function () {

    // Dashboard (Unified for Admin & Camaba)
    Route::get('/', [PendaftaranController::class, 'dashboard'])->name('dashboard');

    // Pendaftaran Management (Admin focus for now)
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/', [PendaftaranController::class, 'index'])->name('index');
        Route::get('/paginate', [PendaftaranController::class, 'paginate'])->name('paginate');
        Route::get('/{pendaftaran}', [PendaftaranController::class, 'show'])->name('show');
        Route::get('/{pendaftaran}/update-status', [PendaftaranController::class, 'updateStatusForm'])->name('update-status-form');
        Route::post('/{pendaftaran}/update-status', [PendaftaranController::class, 'updateStatus'])->name('update-status');
        Route::get('/verify-document/{document}/form', [PendaftaranController::class, 'verifyDocumentForm'])->name('verify-document-form');
        Route::post('/verify-document/{document}', [PendaftaranController::class, 'verifyDocument'])->name('verify-document');
    });

    // Master Data: Periode
    Route::prefix('periode')->name('periode.')->group(function () {
        Route::get('/paginate', [App\Http\Controllers\Pmb\PeriodeController::class, 'paginate'])->name('paginate');
    });
    Route::resource('periode', App\Http\Controllers\Pmb\PeriodeController::class)->parameters(['periode' => 'periode']);

    // Master Data: Jalur
    Route::prefix('jalur')->name('jalur.')->group(function () {
        Route::get('/paginate', [App\Http\Controllers\Pmb\JalurController::class, 'paginate'])->name('paginate');
    });
    Route::resource('jalur', App\Http\Controllers\Pmb\JalurController::class)->parameters(['jalur' => 'jalur']);

    // Master Data: Prodi
    Route::prefix('prodi')->name('prodi.')->group(function () {
        Route::get('/paginate', [App\Http\Controllers\Pmb\ProdiController::class, 'paginate'])->name('paginate');
    });
    Route::resource('prodi', App\Http\Controllers\Pmb\ProdiController::class)->parameters(['prodi' => 'prodi']);

    // Master Data: Jenis Dokumen
    Route::prefix('jenis-dokumen')->name('jenis-dokumen.')->group(function () {
        Route::get('/paginate', [App\Http\Controllers\Pmb\JenisDokumenController::class, 'paginate'])->name('paginate');
    });
    Route::resource('jenis-dokumen', App\Http\Controllers\Pmb\JenisDokumenController::class)->parameters(['jenis-dokumen' => 'jenis_dokumen']);

    // Filter/Mapping: Syarat Dokumen Jalur
    Route::prefix('syarat-jalur')->name('syarat-jalur.')->group(function () {
        Route::get('/', [App\Http\Controllers\Pmb\SyaratDokumenJalurController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Pmb\SyaratDokumenJalurController::class, 'store'])->name('store');
        Route::delete('/{syarat}', [App\Http\Controllers\Pmb\SyaratDokumenJalurController::class, 'destroy'])->name('destroy');
    });

    // Camaba Experience
    Route::prefix('camaba')->name('camaba.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Pmb\CamabaController::class, 'dashboard'])->name('dashboard');
        Route::get('/register', [App\Http\Controllers\Pmb\CamabaController::class, 'create'])->name('register');
        Route::post('/register', [App\Http\Controllers\Pmb\CamabaController::class, 'store'])->name('store');

        // Payment
        Route::get('/payment', [App\Http\Controllers\Pmb\CamabaController::class, 'payment'])->name('payment');
        Route::post('/payment/confirm', [App\Http\Controllers\Pmb\CamabaController::class, 'confirmPayment'])->name('confirm-payment');

        // Document Upload
        Route::get('/upload', [App\Http\Controllers\Pmb\CamabaController::class, 'upload'])->name('upload');
        Route::get('/upload/form', [App\Http\Controllers\Pmb\CamabaController::class, 'uploadForm'])->name('upload-form');
        Route::post('/upload/do', [App\Http\Controllers\Pmb\CamabaController::class, 'doUpload'])->name('do-upload');
        Route::post('/upload/finalize', [App\Http\Controllers\Pmb\CamabaController::class, 'finalizeFiles'])->name('finalize-files');

        // Result
        Route::get('/exam-card', [App\Http\Controllers\Pmb\CamabaController::class, 'examCard'])->name('exam-card');
    });

    // Verification Flow (Admin)
    Route::prefix('verification')->name('verification.')->group(function () {
        Route::get('/payments', [App\Http\Controllers\Pmb\VerificationController::class, 'payments'])->name('payments');
        Route::get('/payments/paginate', [App\Http\Controllers\Pmb\VerificationController::class, 'paginatePayments'])->name('paginate-payments');
        Route::get('/payments/{pembayaran}/form', [App\Http\Controllers\Pmb\VerificationController::class, 'paymentForm'])->name('payment-form');
        Route::post('/payments/{pembayaran}/verify', [App\Http\Controllers\Pmb\VerificationController::class, 'verifyPayment'])->name('verify-payment');

        Route::get('/documents', [App\Http\Controllers\Pmb\VerificationController::class, 'documents'])->name('documents');
        Route::get('/documents/paginate', [App\Http\Controllers\Pmb\VerificationController::class, 'paginateDocuments'])->name('paginate-documents');
    });

    // CBT Session Management
    Route::prefix('sesi-ujian')->name('sesi-ujian.')->group(function () {
        Route::get('/', [App\Http\Controllers\Pmb\SesiUjianController::class, 'index'])->name('index');
        Route::get('/paginate', [App\Http\Controllers\Pmb\SesiUjianController::class, 'paginate'])->name('paginate');
        Route::get('/create', [App\Http\Controllers\Pmb\SesiUjianController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Pmb\SesiUjianController::class, 'store'])->name('store');
        Route::get('/{sesi}/edit', [App\Http\Controllers\Pmb\SesiUjianController::class, 'edit'])->name('edit');
        Route::put('/{sesi}', [App\Http\Controllers\Pmb\SesiUjianController::class, 'update'])->name('update');
        Route::delete('/{sesi}', [App\Http\Controllers\Pmb\SesiUjianController::class, 'destroy'])->name('destroy');
    });

    // Master Data (Periode, Jalur, Prodi, etc.) - To be implemented
});
