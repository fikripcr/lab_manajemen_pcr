<?php

use App\Http\Controllers\Survei\FormBuilderController;
use App\Http\Controllers\Survei\FormPlayerController;
use App\Http\Controllers\Survei\SurveiController;
use Illuminate\Support\Facades\Route;

// --- Public Player Routes (no auth required for guest-accessible surveys) ---
Route::prefix('survei')->name('survei.')->group(function () {
    Route::get('/isi/{slug}', [FormPlayerController::class, 'show'])->name('public.show');
    Route::post('/isi/{slug}', [FormPlayerController::class, 'store'])->name('public.store');
    Route::get('/thankyou/{slug}', [FormPlayerController::class, 'thankyou'])->name('public.thankyou');
});

// --- Admin/Authenticated Routes ---
Route::middleware(['auth'])->prefix('survei')->name('survei.')->group(function () {
    // Survei CRUD
    Route::get('/', [SurveiController::class, 'index'])->name('index');
    Route::get('/paginate', [SurveiController::class, 'paginate'])->name('paginate');
    Route::get('/create', [SurveiController::class, 'create'])->name('create');
    Route::post('/', [SurveiController::class, 'store'])->name('store');
    Route::get('/{survei}/edit', [SurveiController::class, 'edit'])->name('edit');
    Route::put('/{survei}', [SurveiController::class, 'update'])->name('update');
    Route::delete('/{survei}', [SurveiController::class, 'destroy'])->name('destroy');

    Route::post('/{survei}/toggle-status', [SurveiController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{survei}/duplicate', [SurveiController::class, 'duplicate'])->name('duplicate');
    Route::get('/{survei}/export', [SurveiController::class, 'export'])->name('export');
    Route::get('/{survei}/responses', [SurveiController::class, 'responses'])->name('responses');

    // Preview
    Route::get('/{survei}/preview', [FormBuilderController::class, 'preview'])->name('preview');

    // Form Builder
    Route::get('/{survei}/builder', [FormBuilderController::class, 'index'])->name('builder');
    Route::post('/{survei}/halaman', [FormBuilderController::class, 'storeHalaman'])->name('halaman.store');
    Route::put('/halaman/{halaman}', [FormBuilderController::class, 'updateHalaman'])->name('halaman.update');
    Route::delete('/halaman/{halaman}', [FormBuilderController::class, 'destroyHalaman'])->name('halaman.destroy');
    Route::post('/halaman/reorder', [FormBuilderController::class, 'reorderHalaman'])->name('halaman.reorder');

    Route::post('/{survei}/pertanyaan', [FormBuilderController::class, 'storePertanyaan'])->name('pertanyaan.store');
    Route::put('/pertanyaan/{pertanyaan}', [FormBuilderController::class, 'updatePertanyaan'])->name('pertanyaan.update');
    Route::delete('/pertanyaan/{pertanyaan}', [FormBuilderController::class, 'destroyPertanyaan'])->name('pertanyaan.destroy');
    Route::post('/pertanyaan/reorder', [FormBuilderController::class, 'reorderPertanyaan'])->name('pertanyaan.reorder');
});
