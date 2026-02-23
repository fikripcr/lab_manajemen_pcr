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
    Route::post('rapat/peserta/{peserta}/resend-invite', [App\Http\Controllers\Event\RapatController::class, 'resendInvitation'])->name('rapat.peserta.resend-invite');
    Route::get('rapat/{rapat}/pdf', [App\Http\Controllers\Event\RapatController::class, 'generatePdf'])->name('rapat.generate-pdf');
    Route::patch('rapat/peserta/{peserta}/toggle-attendance', [App\Http\Controllers\Event\RapatController::class, 'toggleAttendance'])->name('rapat.peserta.toggle-attendance');
    Route::resource('rapat', App\Http\Controllers\Event\RapatController::class);

    // Rapat Peserta (AJAX Modal support)
    Route::resource('rapat/peserta', App\Http\Controllers\Event\RapatPesertaController::class)->only(['create', 'edit', 'store', 'update', 'destroy']);

    // Rapat Agenda (AJAX Modal support)
    Route::get('rapat/{rapat}/agenda/create', [App\Http\Controllers\Event\RapatAgendaController::class, 'create'])->name('rapat.agenda.create');
    Route::resource('rapat/agenda', App\Http\Controllers\Event\RapatAgendaController::class)->only(['create', 'edit', 'store', 'update', 'destroy']);

    // Rapat Entitas (Nested Resource)
    Route::resource('rapat/{rapat}/entitas', App\Http\Controllers\Event\RapatEntitasController::class)
        ->names('rapat.entitas')
        ->except(['index', 'show']);
    Route::get('rapat/{rapat}/entitas', [App\Http\Controllers\Event\RapatEntitasController::class, 'index'])->name('rapat.entitas.index');
    Route::get('rapat/{rapat}/entitas/data', [App\Http\Controllers\Event\RapatEntitasController::class, 'data'])->name('rapat.entitas.data');

    // --- Global Buku Tamu (All Events) ---
    Route::get('tamus', [App\Http\Controllers\Event\EventTamuController::class, 'index'])->name('tamus.index');
    Route::get('tamus/data', [App\Http\Controllers\Event\EventTamuController::class, 'paginate'])->name('tamus.data');
    Route::get('tamus/create', [App\Http\Controllers\Event\EventTamuController::class, 'create'])->name('tamus.create');
    Route::post('tamus', [App\Http\Controllers\Event\EventTamuController::class, 'store'])->name('tamus.store');
    Route::get('tamus/{tamu}/edit', [App\Http\Controllers\Event\EventTamuController::class, 'edit'])->name('tamus.edit');
    Route::put('tamus/{tamu}', [App\Http\Controllers\Event\EventTamuController::class, 'update'])->name('tamus.update');
    Route::delete('tamus/{tamu}', [App\Http\Controllers\Event\EventTamuController::class, 'destroy'])->name('tamus.destroy');

    // --- New Kegiatan Module Features ---

    // Buku Tamu Token Generate/Revoke (auth)
    Route::post('events/{event}/buku-tamu/generate', [App\Http\Controllers\Event\EventTamuController::class, 'generateToken'])->name('Kegiatans.buku-tamu.generate');
    Route::delete('events/{event}/buku-tamu/revoke', [App\Http\Controllers\Event\EventTamuController::class, 'revokeToken'])->name('Kegiatans.buku-tamu.revoke');

    // Kegiatan (Event)
    Route::get('events/data', [App\Http\Controllers\Event\EventController::class, 'paginate'])->name('Kegiatans.data');
    Route::resource('events', App\Http\Controllers\Event\EventController::class)->names('Kegiatans');

    // Event Team (AJAX Only) - Nested under events
    Route::prefix('events/{event}')->name('Kegiatans.')->group(function () {
        Route::get('teams/create', [App\Http\Controllers\Event\EventTeamController::class, 'create'])->name('teams.create');
        Route::post('teams', [App\Http\Controllers\Event\EventTeamController::class, 'store'])->name('teams.store');
        Route::get('teams/{team}/edit', [App\Http\Controllers\Event\EventTeamController::class, 'edit'])->name('teams.edit');
        Route::put('teams/{team}', [App\Http\Controllers\Event\EventTeamController::class, 'update'])->name('teams.update');
        Route::delete('teams/{team}', [App\Http\Controllers\Event\EventTeamController::class, 'destroy'])->name('teams.destroy');
    });

    // Buku Tamu / Guest - Nested under events
    Route::prefix('events/{event}')->name('Kegiatans.')->group(function () {
        Route::get('tamus', [App\Http\Controllers\Event\EventTamuController::class, 'index'])->name('tamus.index');
        Route::get('tamus/create', [App\Http\Controllers\Event\EventTamuController::class, 'create'])->name('tamus.create');
        Route::post('tamus', [App\Http\Controllers\Event\EventTamuController::class, 'store'])->name('tamus.store');
        Route::get('tamus/{tamu}/edit', [App\Http\Controllers\Event\EventTamuController::class, 'edit'])->name('tamus.edit');
        Route::put('tamus/{tamu}', [App\Http\Controllers\Event\EventTamuController::class, 'update'])->name('tamus.update');
        Route::delete('tamus/{tamu}', [App\Http\Controllers\Event\EventTamuController::class, 'destroy'])->name('tamus.destroy');
    });
});

// Registration Routes (Public)
Route::prefix('event')->name('Kegiatan.')->group(function () {
    Route::get('events/{event}/registrasi', [App\Http\Controllers\Event\EventTamuController::class, 'registration'])->name('Kegiatans.registration');
    Route::post('events/{event}/registrasi', [App\Http\Controllers\Event\EventTamuController::class, 'storeRegistration'])->name('Kegiatans.registration.store');
});

// Buku Tamu Public - Permanent link using encrypted event ID (no auth required)
Route::get('attendance/{hashid}', [App\Http\Controllers\Event\EventTamuController::class, 'attendanceForm'])->name('attendance.form');
Route::post('attendance/{hashid}', [App\Http\Controllers\Event\EventTamuController::class, 'attendanceStore'])->name('attendance.store');
