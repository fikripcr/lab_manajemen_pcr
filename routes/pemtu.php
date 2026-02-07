<?php

use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 SPMI (PEMTU) Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('pemtu')->name('pemtu.')->group(function () {

    // Label Types (modal forms only - no index page)
    Route::resource('label-types', App\Http\Controllers\Pemtu\LabelTypeController::class)->except(['index', 'show']);

    // Labels
    Route::get('api/labels', [App\Http\Controllers\Pemtu\LabelController::class, 'paginate'])->name('labels.data');
    Route::resource('labels', App\Http\Controllers\Pemtu\LabelController::class);

    // Org Units
    Route::get('api/org-units', [App\Http\Controllers\Pemtu\OrgUnitController::class, 'paginate'])->name('org-units.data');
    Route::post('org-units/{id}/toggle-status', [App\Http\Controllers\Pemtu\OrgUnitController::class, 'toggleStatus'])->name('org-units.toggle-status');
    Route::post('org-units/{id}/set-auditee', [App\Http\Controllers\Pemtu\OrgUnitController::class, 'setAuditee'])->name('org-units.set-auditee');
    Route::post('org-units/reorder', [App\Http\Controllers\Pemtu\OrgUnitController::class, 'reorder'])->name('org-units.reorder');
    Route::resource('org-units', App\Http\Controllers\Pemtu\OrgUnitController::class);

    // Personils
    Route::get('api/personils', [App\Http\Controllers\Pemtu\PersonilController::class, 'paginate'])->name('personils.data');
    Route::match(['get', 'post'], 'personils/import', [App\Http\Controllers\Pemtu\PersonilController::class, 'import'])->name('personils.import');
    Route::resource('personils', App\Http\Controllers\Pemtu\PersonilController::class);

    // Dokumen & Structure
    // Dokumen & Structure
    Route::post('dokumens/reorder', [App\Http\Controllers\Pemtu\DokumenController::class, 'reorder'])->name('dokumens.reorder');
    Route::get('dokumens/create-standar', [App\Http\Controllers\Pemtu\DokumenController::class, 'createStandar'])->name('dokumens.create-standar');
    Route::get('dokumens/{id}/children-data', [App\Http\Controllers\Pemtu\DokumenController::class, 'childrenData'])->name('dokumens.children-data');
    Route::resource('dokumens', App\Http\Controllers\Pemtu\DokumenController::class);

    // Sub-Documents (DokSub)
    Route::get('dok-subs/{dokId}/data', [App\Http\Controllers\Pemtu\DokSubController::class, 'data'])->name('dok-subs.data');
    Route::resource('dok-subs', App\Http\Controllers\Pemtu\DokSubController::class);

    // Indikators
    Route::get('api/indikators', [App\Http\Controllers\Pemtu\IndikatorController::class, 'paginate'])->name('indikators.data');
    Route::resource('indikators', App\Http\Controllers\Pemtu\IndikatorController::class);

});
