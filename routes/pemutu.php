<?php

use App\Http\Controllers\Pemutu\DashboardController;
use App\Http\Controllers\Pemutu\DokumenApprovalController;
use App\Http\Controllers\Pemutu\DokumenController;
use App\Http\Controllers\Pemutu\DokumenSpmiController;
use App\Http\Controllers\Pemutu\IndikatorController;
use App\Http\Controllers\Pemutu\LabelController;
use App\Http\Controllers\Pemutu\LabelTypeController;
use App\Http\Controllers\Pemutu\MyKpiController;
use App\Http\Controllers\Pemutu\PegawaiController;
use App\Http\Controllers\Pemutu\PeriodeKpiController;
use App\Http\Controllers\Pemutu\PeriodeSpmiController;
use App\Http\Controllers\Pemutu\RenopController;
use App\Http\Controllers\Pemutu\TimMutuController;
use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 SPMI (PEMTU) Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('pemutu')->name('pemutu.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Periode KPI
    Route::get('periode-kpis/data', [PeriodeKpiController::class, 'data'])->name('periode-kpis.data');
    Route::post('periode-kpis/{periodeKpi}/activate', [PeriodeKpiController::class, 'activate'])->name('periode-kpis.activate');
    Route::resource('periode-kpis', PeriodeKpiController::class);

    // Label Types (modal forms only - no index page)
    Route::resource('label-types', LabelTypeController::class)->except(['index', 'show']);

    // Labels
    Route::get('api/labels', [LabelController::class, 'paginate'])->name('labels.data');
    Route::resource('labels', LabelController::class);

    // Pegawai
    Route::get('api/pegawai', [PegawaiController::class, 'paginate'])->name('pegawai.data');
    Route::match(['get', 'post'], 'pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::resource('pegawai', PegawaiController::class);

    // Dokumen & Structure (REFACTORED WORKSPACE)
    Route::post('dokumens/reorder', [DokumenController::class, 'reorder'])->name('dokumens.reorder');
    Route::get('dokumen-spmi', [DokumenSpmiController::class, 'index'])->name('dokumens.index');
    Route::get('dokumen-spmi/create', [DokumenSpmiController::class, 'create'])->name('dokumen-spmi.create');
    Route::post('dokumen-spmi/store', [DokumenSpmiController::class, 'store'])->name('dokumen-spmi.store');
    Route::get('dokumen-spmi/{type}/{id}', [DokumenSpmiController::class, 'show'])->name('dokumen-spmi.show');
    Route::get('dokumen-spmi/{type}/{id}/edit', [DokumenSpmiController::class, 'edit'])->name('dokumen-spmi.edit');
    Route::put('dokumen-spmi/{type}/{id}', [DokumenSpmiController::class, 'update'])->name('dokumen-spmi.update');
    Route::delete('dokumen-spmi/{type}/{id}', [DokumenSpmiController::class, 'destroy'])->name('dokumen-spmi.destroy');
    Route::get('dokumen-spmi/{type}/{id}/children', [DokumenSpmiController::class, 'childrenData'])->name('dokumen-spmi.children-data');

    // Sub-Documents (DokSub) - Handled dynamically by DokumenSpmiController now
    // Route::get('dok-subs/{dokumen}/data', [DokSubController::class, 'data'])->name('dok-subs.data');
    // Route::resource('dok-subs', DokSubController::class);

    // Indikators
    Route::get('api/indikators', [IndikatorController::class, 'paginate'])->name('indikators.data');
    Route::resource('indikators', IndikatorController::class);

    // Standar (Indikator Standar)
    Route::get('api/standar', [App\Http\Controllers\Pemutu\StandarController::class, 'paginate'])->name('standar.data');
    // ...
    Route::get('standar/{id}/assign', [App\Http\Controllers\Pemutu\StandarController::class, 'assign'])->name('standar.assign');
    Route::post('standar/{id}/assign', [App\Http\Controllers\Pemutu\StandarController::class, 'storeAssignment'])->name('standar.assign.store');
    Route::resource('standar', App\Http\Controllers\Pemutu\StandarController::class);

    // Document Approvals
    Route::get('dokumens/{dokumen}/approve', [DokumenApprovalController::class, 'create'])->name('dokumens.approve.create');
    // ...
    Route::post('dokumens/{dokumen}/approve', [DokumenApprovalController::class, 'store'])->name('dokumens.approve');
    Route::delete('dokumens/approval/{approval}', [DokumenApprovalController::class, 'destroy'])->name('dokumens.approval.destroy');

    // Period SPMI (PEPP Cycle)
    Route::get('api/periode-spmi', [PeriodeSpmiController::class, 'paginate'])->name('periode-spmis.data');
    Route::resource('periode-spmis', PeriodeSpmiController::class);

    // Tim Mutu
    Route::get('tim-mutu', [TimMutuController::class, 'index'])->name('tim-mutu.index');
    Route::get('tim-mutu/search-pegawai', [TimMutuController::class, 'searchPegawai'])->name('tim-mutu.search-pegawai');
    Route::get('tim-mutu/{periode}/manage', [TimMutuController::class, 'manage'])->name('tim-mutu.manage');
    Route::get('tim-mutu/{periode}/unit/{unit}/edit', [TimMutuController::class, 'editUnit'])->name('tim-mutu.edit-unit');
    Route::post('tim-mutu/{periode}/unit/{unit}', [TimMutuController::class, 'storeUnit'])->name('tim-mutu.store-unit');

    // Renop (Rencana Operasional)
    Route::get('renop/create', [RenopController::class, 'create'])->name('renop.create');
    Route::post('renop', [RenopController::class, 'store'])->name('renop.store');
    Route::get('renop', [RenopController::class, 'index'])->name('renop.index');

    // My KPI
    Route::get('mykpi', [MyKpiController::class, 'index'])->name('mykpi.index');
    Route::get('mykpi/{kpi}/edit', [MyKpiController::class, 'edit'])->name('mykpi.edit');
    Route::put('mykpi/{kpi}', [MyKpiController::class, 'update'])->name('mykpi.update');

    // Evaluasi Diri
    Route::get('evaluasi-diri', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'index'])->name('evaluasi-diri.index');
    Route::get('evaluasi-diri/{periode}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'show'])->name('evaluasi-diri.show');
    Route::get('evaluasi-diri/{periode}/data', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'data'])->name('evaluasi-diri.data');
    Route::get('evaluasi-diri/{indikator}/edit', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'edit'])->name('evaluasi-diri.edit');
    Route::post('evaluasi-diri/{indikator}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'update'])->name('evaluasi-diri.update');
});
