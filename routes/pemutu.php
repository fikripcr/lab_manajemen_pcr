<?php

use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 SPMI (PEMTU) Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('pemutu')->name('pemutu.')->group(function () {
    // Rapat
    Route::get('rapat/data', [App\Http\Controllers\Pemutu\RapatController::class, 'paginate'])->name('rapat.data');
    Route::resource('rapat', App\Http\Controllers\Pemutu\RapatController::class);

    // Rapat Peserta
    Route::get('rapat/peserta/data', [App\Http\Controllers\Pemutu\RapatPesertaController::class, 'data'])->name('rapat.peserta.data');
    Route::resource('rapat/peserta', App\Http\Controllers\Pemutu\RapatPesertaController::class);

    // Rapat Agenda
    Route::get('rapat/agenda/data', [App\Http\Controllers\Pemutu\RapatAgendaController::class, 'data'])->name('rapat.agenda.data');
    Route::resource('rapat/agenda', App\Http\Controllers\Pemutu\RapatAgendaController::class);

    // Label Types (modal forms only - no index page)
    Route::resource('label-types', App\Http\Controllers\Pemutu\LabelTypeController::class)->except(['index', 'show']);

    // Labels
    Route::get('api/labels', [App\Http\Controllers\Pemutu\LabelController::class, 'paginate'])->name('labels.data');
    Route::resource('labels', App\Http\Controllers\Pemutu\LabelController::class);

    // Org Units
    Route::get('api/org-units', [App\Http\Controllers\Pemutu\OrgUnitController::class, 'paginate'])->name('org-units.data');
    Route::post('org-units/{org_unit}/toggle-status', [App\Http\Controllers\Pemutu\OrgUnitController::class, 'toggleStatus'])->name('org-units.toggle-status');
    Route::post('org-units/{org_unit}/set-auditee', [App\Http\Controllers\Pemutu\OrgUnitController::class, 'setAuditee'])->name('org-units.set-auditee');
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
    Route::get('dokumens/{dokumen}/children-data', [App\Http\Controllers\Pemutu\DokumenController::class, 'childrenData'])->name('dokumens.children-data');
    Route::get('dokumens/{dokumen}/renop-with-indicators', [App\Http\Controllers\Pemutu\DokumenController::class, 'showRenopWithIndicators'])->name('dokumens.show-renop-with-indicators');
    Route::resource('dokumens', App\Http\Controllers\Pemutu\DokumenController::class);

    // Sub-Documents (DokSub)
    Route::get('dok-subs/{dokumen}/data', [App\Http\Controllers\Pemutu\DokSubController::class, 'data'])->name('dok-subs.data');
    Route::resource('dok-subs', App\Http\Controllers\Pemutu\DokSubController::class);

    // Indikators
    Route::get('api/indikators', [App\Http\Controllers\Pemutu\IndikatorController::class, 'paginate'])->name('indikators.data');
    Route::resource('indikators', App\Http\Controllers\Pemutu\IndikatorController::class);

    // KPI (Sasaran Kinerja)
    Route::get('api/kpi', [App\Http\Controllers\Pemutu\KpiController::class, 'paginate'])->name('kpi.data');
    Route::resource('kpi', App\Http\Controllers\Pemutu\KpiController::class);

    // Document Approvals
    Route::post('dokumens/{dokumen}/approve', [App\Http\Controllers\Pemutu\DokumenApprovalController::class, 'store'])->name('dokumens.approve');

    // Period SPMI (PEPP Cycle)
    Route::get('api/periode-spmi', [App\Http\Controllers\Pemutu\PeriodeSpmiController::class, 'paginate'])->name('periode-spmis.data');
    Route::resource('periode-spmis', App\Http\Controllers\Pemutu\PeriodeSpmiController::class);

    // Renop (Rencana Operasional)
    Route::get('renop/create', [App\Http\Controllers\Pemutu\RenopController::class, 'create'])->name('renop.create');
    Route::post('renop', [App\Http\Controllers\Pemutu\RenopController::class, 'store'])->name('renop.store');
    Route::get('renop', [App\Http\Controllers\Pemutu\RenopController::class, 'index'])->name('renop.index');
    // Assignment removed from Renop

    // Standard & Performance Indicators

    // My KPI
    Route::get('mykpi', [App\Http\Controllers\Pemutu\MyKpiController::class, 'index'])->name('mykpi.index');
    Route::get('mykpi/{id}/edit', [App\Http\Controllers\Pemutu\MyKpiController::class, 'edit'])->name('mykpi.edit');
    Route::put('mykpi/{id}', [App\Http\Controllers\Pemutu\MyKpiController::class, 'update'])->name('mykpi.update');
});
