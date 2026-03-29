<?php

use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\EventTamuController;
use App\Http\Controllers\Event\EventTeamController;
use App\Http\Controllers\Event\RapatAgendaController;
use App\Http\Controllers\Event\RapatController;
use App\Http\Controllers\Event\RapatEntitasController;
use App\Http\Controllers\Event\RapatPesertaController;
use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 Event & Rapat Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('event')->name('Kegiatan.')->group(function () {

    // Rapat (Meeting) Sub-module
    Route::get('rapat/data', [RapatController::class, 'data'])->name('rapat.data');
    Route::post('rapat/{rapat}/attendance', [RapatController::class, 'updateAttendance'])->name('rapat.update-attendance');
    Route::post('rapat/{rapat}/agenda', [RapatController::class, 'updateAgenda'])->name('rapat.update-agenda');
    Route::post('rapat/{rapat}/agenda/store', [RapatController::class, 'storeAgenda'])->name('rapat.agenda.store');
    Route::get('rapat/{rapat}/officials', [RapatController::class, 'editOfficials'])->name('rapat.edit-officials');
    Route::post('rapat/{rapat}/officials', [RapatController::class, 'updateOfficials'])->name('rapat.update-officials');
    Route::get('rapat/{rapat}/participants/create', [RapatController::class, 'createParticipants'])->name('rapat.participants.create');
    Route::post('rapat/{rapat}/participants', [RapatController::class, 'storeParticipants'])->name('rapat.participants.store');
    Route::post('rapat/peserta/{peserta}/resend-invite', [RapatController::class, 'resendInvitation'])->name('rapat.peserta.resend-invite');
    Route::get('rapat/{rapat}/pdf', [RapatController::class, 'generatePdf'])->name('rapat.generate-pdf');
    Route::patch('rapat/peserta/{peserta}/toggle-attendance', [RapatController::class, 'toggleAttendance'])->name('rapat.peserta.toggle-attendance');
    Route::resource('rapat', RapatController::class);

    // Rapat Peserta (AJAX Modal support)
    Route::resource('rapat/peserta', RapatPesertaController::class)->only(['create', 'edit', 'store', 'update', 'destroy']);

    // Rapat Agenda (AJAX Modal support)
    Route::get('rapat/{rapat}/agenda/create', [RapatAgendaController::class, 'create'])->name('rapat.agenda.create');
    Route::resource('rapat/agenda', RapatAgendaController::class)
        ->only(['edit', 'store', 'update', 'destroy'])
        ->names([
            'edit' => 'rapat.agenda.edit',
            'store' => 'rapat.agenda.store',
            'update' => 'rapat.agenda.update',
            'destroy' => 'rapat.agenda.destroy',
        ]);

    // Rapat Entitas (Nested Resource)
    Route::get('rapat/{rapat}/entitas/search', [RapatEntitasController::class, 'search'])->name('rapat.entitas.search');
    Route::resource('rapat/{rapat}/entitas', RapatEntitasController::class)
        ->names('rapat.entitas')
        ->parameters(['entitas' => 'entitas'])
        ->except(['index', 'show']);
    Route::get('rapat/{rapat}/entitas', [RapatEntitasController::class, 'index'])->name('rapat.entitas.index');
    Route::get('rapat/{rapat}/entitas/data', [RapatEntitasController::class, 'data'])->name('rapat.entitas.data');

    // --- Global Buku Tamu (All Events) ---
    Route::get('tamus', [EventTamuController::class, 'index'])->name('tamus.index');
    Route::get('tamus/data', [EventTamuController::class, 'data'])->name('tamus.data');
    Route::get('tamus/create', [EventTamuController::class, 'create'])->name('tamus.create');
    Route::post('tamus', [EventTamuController::class, 'store'])->name('tamus.store');
    Route::get('tamus/{tamu}/edit', [EventTamuController::class, 'edit'])->name('tamus.edit');
    Route::put('tamus/{tamu}', [EventTamuController::class, 'update'])->name('tamus.update');
    Route::delete('tamus/{tamu}', [EventTamuController::class, 'destroy'])->name('tamus.destroy');

    // --- New Kegiatan Module Features ---

    // Buku Tamu Token Generate/Revoke (auth)
    Route::post('events/{event}/buku-tamu/generate', [EventTamuController::class, 'generateToken'])->name('Kegiatans.buku-tamu.generate');
    Route::delete('events/{event}/buku-tamu/revoke', [EventTamuController::class, 'revokeToken'])->name('Kegiatans.buku-tamu.revoke');

    // Kegiatan (Event)
    Route::get('events/data', [EventController::class, 'data'])->name('Kegiatans.data');
    Route::resource('events', EventController::class)->names('Kegiatans');

    // Event Team (AJAX Only) - Nested under events
    Route::prefix('events/{event}')->name('Kegiatans.')->group(function () {
        Route::get('teams/create', [EventTeamController::class, 'create'])->name('teams.create');
        Route::post('teams', [EventTeamController::class, 'store'])->name('teams.store');
        Route::get('teams/{team}/edit', [EventTeamController::class, 'edit'])->name('teams.edit');
        Route::put('teams/{team}', [EventTeamController::class, 'update'])->name('teams.update');
        Route::delete('teams/{team}', [EventTeamController::class, 'destroy'])->name('teams.destroy');
    });

    // Buku Tamu / Guest - Nested under events
    Route::prefix('events/{event}')->name('Kegiatans.')->group(function () {
        Route::get('tamus', [EventTamuController::class, 'index'])->name('tamus.index');
        Route::get('tamus/create', [EventTamuController::class, 'create'])->name('tamus.create');
        Route::post('tamus', [EventTamuController::class, 'store'])->name('tamus.store');
        Route::get('tamus/{tamu}/edit', [EventTamuController::class, 'edit'])->name('tamus.edit');
        Route::put('tamus/{tamu}', [EventTamuController::class, 'update'])->name('tamus.update');
        Route::delete('tamus/{tamu}', [EventTamuController::class, 'destroy'])->name('tamus.destroy');
    });
});

// Registration Routes (Public)
Route::prefix('event')->name('Kegiatan.')->group(function () {
    Route::get('events/{event}/registrasi', [EventTamuController::class, 'registration'])->name('Kegiatans.registration');
    Route::post('events/{event}/registrasi', [EventTamuController::class, 'storeRegistration'])->name('Kegiatans.registration.store');
});

// Buku Tamu Public - Permanent link using encrypted event ID (no auth required)
Route::get('attendance/{hashid}', [EventTamuController::class, 'attendanceForm'])->name('attendance.form');
Route::post('attendance/{hashid}', [EventTamuController::class, 'attendanceStore'])->name('attendance.store');
