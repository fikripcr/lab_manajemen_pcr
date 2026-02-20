<?php

use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 Event & Rapat Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('event')->name('Kegiatan.')->group(function () {

    // Rapat (Meeting) Sub-module
    Route::get('rapat/data', [App\Http\Controllers\Event\RapatController::class, 'paginate'])->name('rapat.data');
    Route::post('rapat/{rapat}/attendance', [App\Http\Controllers\Event\RapatController::class, 'updateAttendance'])->name('rapat.update-attendance');
    Route::post('rapat/{rapat}/agenda', [App\Http\Controllers\Event\RapatController::class, 'updateAgenda'])->name('rapat.update-agenda');
    Route::post('rapat/{rapat}/agenda/store', [App\Http\Controllers\Event\RapatController::class, 'storeAgenda'])->name('rapat.agenda.store');
    Route::post('rapat/{rapat}/officials', [App\Http\Controllers\Event\RapatController::class, 'updateOfficials'])->name('rapat.update-officials');
    Route::post('rapat/{rapat}/participants', [App\Http\Controllers\Event\RapatController::class, 'storeParticipants'])->name('rapat.participants.store');
    Route::get('rapat/{rapat}/pdf', [App\Http\Controllers\Event\RapatController::class, 'generatePdf'])->name('rapat.generate-pdf');
    Route::resource('rapat', App\Http\Controllers\Event\RapatController::class);

    // Rapat Peserta (AJAX Modal support)
    Route::resource('rapat/peserta', App\Http\Controllers\Event\RapatPesertaController::class)->only(['create', 'edit', 'store', 'update', 'destroy']);

    // Rapat Agenda (AJAX Modal support)
    Route::resource('rapat/agenda', App\Http\Controllers\Event\RapatAgendaController::class)->only(['create', 'edit', 'store', 'update', 'destroy']);

    // Rapat Entitas (AJAX Modal support)
    Route::resource('rapat/entitas', App\Http\Controllers\Event\RapatEntitasController::class)->only(['create', 'edit', 'store', 'update', 'destroy']);

    // --- New Kegiatan Module Features ---

    // Kegiatan (Event)
    Route::get('events/data', [App\Http\Controllers\Event\EventController::class, 'paginate'])->name('Kegiatans.data');
    Route::resource('events', App\Http\Controllers\Event\EventController::class)->names('Kegiatans');

    // Event Team (AJAX Only)
    Route::resource('teams', App\Http\Controllers\Event\EventTeamController::class)->only(['create', 'edit', 'store', 'update', 'destroy']);

    // Buku Tamu / Guest
    Route::get('tamus/data', [App\Http\Controllers\Event\EventTamuController::class, 'paginate'])->name('tamus.data');
    Route::resource('tamus', App\Http\Controllers\Event\EventTamuController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);
});

// Registration Routes (Public)
Route::prefix('event')->name('Kegiatan.')->group(function () {
    Route::get('events/{event}/registrasi', [App\Http\Controllers\Event\EventTamuController::class, 'registration'])->name('Kegiatans.registration');
    Route::post('events/{event}/registrasi', [App\Http\Controllers\Event\EventTamuController::class, 'storeRegistration'])->name('Kegiatans.registration.store');
});
