<?php

use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 SPMI (PEMTU) Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('pemutu')->name('pemutu.')->group(function () {

    // Label Types (modal forms only - no index page)
    Route::resource('label-types', App\Http\Controllers\Pemutu\LabelTypeController::class)->except(['index', 'show']);

    // Labels
    Route::get('api/labels', [App\Http\Controllers\Pemutu\LabelController::class, 'paginate'])->name('labels.data');
    Route::resource('labels', App\Http\Controllers\Pemutu\LabelController::class);

    // Org Units
    Route::get('api/org-units', [App\Http\Controllers\Pemutu\OrgUnitController::class, 'paginate'])->name('org-units.data');
    Route::post('org-units/{id}/toggle-status', [App\Http\Controllers\Pemutu\OrgUnitController::class, 'toggleStatus'])->name('org-units.toggle-status');
    Route::post('org-units/{id}/set-auditee', [App\Http\Controllers\Pemutu\OrgUnitController::class, 'setAuditee'])->name('org-units.set-auditee');
    Route::post('org-units/reorder', [App\Http\Controllers\Pemutu\OrgUnitController::class, 'reorder'])->name('org-units.reorder');
    Route::resource('org-units', App\Http\Controllers\Pemutu\OrgUnitController::class);

    // Personils
    Route::get('api/personils', [App\Http\Controllers\Pemutu\PersonilController::class, 'paginate'])->name('personils.data');
    Route::match(['get', 'post'], 'personils/import', [App\Http\Controllers\Pemutu\PersonilController::class, 'import'])->name('personils.import');
    Route::resource('personils', App\Http\Controllers\Pemutu\PersonilController::class);

    // Dokumen & Structure
    // Dokumen & Structure
    Route::post('dokumens/reorder', [App\Http\Controllers\Pemutu\DokumenController::class, 'reorder'])->name('dokumens.reorder');
    Route::get('dokumens/create-standar', [App\Http\Controllers\Pemutu\DokumenController::class, 'createStandar'])->name('dokumens.create-standar');
    Route::get('dokumens/{id}/children-data', [App\Http\Controllers\Pemutu\DokumenController::class, 'childrenData'])->name('dokumens.children-data');
    Route::resource('dokumens', App\Http\Controllers\Pemutu\DokumenController::class);

    // Sub-Documents (DokSub)
    Route::get('dok-subs/{dokId}/data', [App\Http\Controllers\Pemutu\DokSubController::class, 'data'])->name('dok-subs.data');
    Route::resource('dok-subs', App\Http\Controllers\Pemutu\DokSubController::class);

    // Indikators
    Route::get('api/indikators', [App\Http\Controllers\Pemutu\IndikatorController::class, 'paginate'])->name('indikators.data');
    Route::resource('indikators', App\Http\Controllers\Pemutu\IndikatorController::class);

});
