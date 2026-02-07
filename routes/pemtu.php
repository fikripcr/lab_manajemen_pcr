<?php

use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 SPMI (PEMTU) Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('pemtu')->name('pemtu.')->group(function () {

    // Label Types
    Route::get('api/label-types', [App\Http\Controllers\Pemtu\LabelTypeController::class, 'paginate'])->name('label-types.data');
    Route::resource('label-types', App\Http\Controllers\Pemtu\LabelTypeController::class);

    // Labels
    Route::get('api/labels', [App\Http\Controllers\Pemtu\LabelController::class, 'paginate'])->name('labels.data');
    Route::resource('labels', App\Http\Controllers\Pemtu\LabelController::class);

    // Org Units
    Route::post('org-units/reorder', [App\Http\Controllers\Pemtu\OrgUnitController::class, 'reorder'])->name('org-units.reorder');
    Route::resource('org-units', App\Http\Controllers\Pemtu\OrgUnitController::class);

    // Personils
    Route::get('api/personils', [App\Http\Controllers\Pemtu\PersonilController::class, 'paginate'])->name('personils.data');
    Route::match(['get', 'post'], 'personils/import', [App\Http\Controllers\Pemtu\PersonilController::class, 'import'])->name('personils.import');
    Route::resource('personils', App\Http\Controllers\Pemtu\PersonilController::class);

    // Dokumen & Structure
    Route::post('dokumens/reorder', [App\Http\Controllers\Pemtu\DokumenController::class, 'reorder'])->name('dokumens.reorder');
    Route::get('dokumens/{id}/children-data', [App\Http\Controllers\Pemtu\DokumenController::class, 'childrenData'])->name('dokumens.children-data');
    Route::resource('dokumens', App\Http\Controllers\Pemtu\DokumenController::class);

    // Sub-Documents (DokSub)
    Route::get('dok-subs/{dokId}/data', [App\Http\Controllers\Pemtu\DokSubController::class, 'data'])->name('dok-subs.data');
    Route::resource('dok-subs', App\Http\Controllers\Pemtu\DokSubController::class);

    // Indikators
    Route::get('api/indikators', [App\Http\Controllers\Pemtu\IndikatorController::class, 'paginate'])->name('indikators.data');
    Route::resource('indikators', App\Http\Controllers\Pemtu\IndikatorController::class);

});
