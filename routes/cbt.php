<?php

use App\Http\Controllers\Cbt\ExamExecutionController;
use App\Http\Controllers\Cbt\MataUjiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.expired'])->prefix('cbt')->name('cbt.')->group(function () {

    // Dashboard (Unified for Admin & Camaba)
    Route::get('/', [ExamExecutionController::class, 'dashboard'])->name('dashboard');

    // Monitoring & Reporting
    Route::get('/monitor/{jadwal}', [ExamExecutionController::class, 'monitor'])->name('execute.monitor');
    Route::get('/violations', [ExamExecutionController::class, 'violations'])->name('laporan.pelanggaran');
    Route::get('/api/violations/{riwayat}', [ExamExecutionController::class, 'getViolationsByRiwayat'])->name('api.violations-by-riwayat');

    // API Routes for Exam Execution
    Route::prefix('api')->name('api.')->group(function () {
        Route::post('/save-answer', [ExamExecutionController::class, 'saveAnswerApi'])->name('save-answer');
        Route::post('/submit-exam', [ExamExecutionController::class, 'submitExamApi'])->name('submit-exam');
        Route::post('/log-violation', [ExamExecutionController::class, 'logViolationApi'])->name('log-violation');
        Route::post('/toggle-token/{jadwal}', [ExamExecutionController::class, 'toggleTokenApi'])->name('toggle-token');
    });

    // Mata Uji
    Route::prefix('mata-uji')->name('mata-uji.')->group(function () {
        Route::get('/', [MataUjiController::class, 'index'])->name('index');
        Route::get('/paginate', [MataUjiController::class, 'paginate'])->name('paginate');
        Route::get('/create', [MataUjiController::class, 'create'])->name('create');
        Route::post('/', [MataUjiController::class, 'store'])->name('store');
        Route::get('/{mata_uji}', [MataUjiController::class, 'show'])->name('show');
        Route::get('/{mata_uji}/edit', [MataUjiController::class, 'edit'])->name('edit');
        Route::put('/{mata_uji}', [MataUjiController::class, 'update'])->name('update');
        Route::delete('/{mata_uji}', [MataUjiController::class, 'destroy'])->name('destroy');
    });

    // Soal
    Route::prefix('soal')->name('soal.')->group(function () {
        Route::get('/create/{mata_uji}', [App\Http\Controllers\Cbt\SoalController::class, 'create'])->name('create');
        Route::post('/{mata_uji}', [App\Http\Controllers\Cbt\SoalController::class, 'store'])->name('store');
        Route::get('/{soal}/edit', [App\Http\Controllers\Cbt\SoalController::class, 'edit'])->name('edit');
        Route::put('/{soal}', [App\Http\Controllers\Cbt\SoalController::class, 'update'])->name('update');
        Route::delete('/{soal}', [App\Http\Controllers\Cbt\SoalController::class, 'destroy'])->name('destroy');
    });

    // Paket Ujian
    Route::prefix('paket')->name('paket.')->group(function () {
        Route::get('/', [App\Http\Controllers\Cbt\PaketUjianController::class, 'index'])->name('index');
        Route::get('/paginate', [App\Http\Controllers\Cbt\PaketUjianController::class, 'paginate'])->name('paginate');
        Route::get('/create', [App\Http\Controllers\Cbt\PaketUjianController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Cbt\PaketUjianController::class, 'store'])->name('store');
        Route::get('/{paket}', [App\Http\Controllers\Cbt\PaketUjianController::class, 'show'])->name('show');
        Route::get('/{paket}/edit', [App\Http\Controllers\Cbt\PaketUjianController::class, 'edit'])->name('edit');
        Route::put('/{paket}', [App\Http\Controllers\Cbt\PaketUjianController::class, 'update'])->name('update');
        Route::delete('/{paket}', [App\Http\Controllers\Cbt\PaketUjianController::class, 'destroy'])->name('destroy');

        Route::post('/{paket}/add-soal', [App\Http\Controllers\Cbt\PaketUjianController::class, 'addSoal'])->name('add-soal');
        Route::delete('/{paket}/remove-soal/{komposisi}', [App\Http\Controllers\Cbt\PaketUjianController::class, 'removeSoal'])->name('remove-soal');
    });

    // Jadwal Ujian
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'index'])->name('index');
        Route::get('/paginate', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'paginate'])->name('paginate');
        Route::get('/create', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'store'])->name('store');
        Route::get('/{jadwal}/edit', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'edit'])->name('edit');
        Route::put('/{jadwal}', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'update'])->name('update');
        Route::delete('/{jadwal}', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'destroy'])->name('destroy');

        Route::post('/{jadwal}/generate-token', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'generateToken'])->name('generate-token');
        Route::post('/{jadwal}/toggle-token', [App\Http\Controllers\Cbt\JadwalUjianController::class, 'toggleToken'])->name('toggle-token');
    });

    // Student Exam Execution (Hybrid)
    Route::prefix('execute')->name('execute.')->group(function () {
        Route::get('/token/{jadwal}', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'tokenForm'])->name('token-form');
        Route::post('/validate/{jadwal}', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'validateToken'])->name('validate-token');

        Route::get('/{jadwal}', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'welcome'])->name('welcome');
        Route::post('/{jadwal}/begin', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'start'])->name('start');
        Route::get('/test/{jadwal}', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'testExam'])->name('test-exam');
        Route::get('/{jadwal}/finished', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'finished'])->name('finished');
        Route::post('/save/{riwayat}', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'saveAnswer'])->name('save');
        Route::post('/submit/{riwayat}', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'submit'])->name('submit');
        Route::post('/reset/{jadwal}', [App\Http\Controllers\Cbt\ExamExecutionController::class, 'resetAdminExam'])->name('reset-admin');
    });
});
