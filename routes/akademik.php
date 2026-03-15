<?php

use App\Http\Controllers\Akademik\MahasiswaController;
use App\Http\Controllers\Akademik\MataKuliahController;
use App\Http\Controllers\Akademik\SemesterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.expired'])->group(function () {

    // Akademik Data Modules
    Route::prefix('akademik')->name('akademik.')->group(function () {

        // Mahasiswa
        Route::get('/mahasiswa/data', [MahasiswaController::class, 'data'])->name('mahasiswa.data');
        Route::post('/mahasiswa/{mahasiswa}/generate-user', [MahasiswaController::class, 'generateUser'])->name('mahasiswa.generate-user');
        Route::resource('mahasiswa', MahasiswaController::class);

        // Semester
        Route::get('semesters/data', [SemesterController::class, 'data'])->name('semesters.data');
        Route::get('semesters/create-modal', [SemesterController::class, 'createModal'])->name('semesters.create-modal');
        Route::get('semesters/edit-modal/{semesterid?}', [SemesterController::class, 'editModal'])->name('semesters.edit-modal.show');
        Route::resource('semesters', SemesterController::class);

        // Mata Kuliah
        Route::get('mata-kuliah/data', [MataKuliahController::class, 'data'])->name('mata-kuliah.data');
        Route::resource('mata-kuliah', MataKuliahController::class);

    });

});
