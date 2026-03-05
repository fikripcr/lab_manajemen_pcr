<?php

use App\Http\Controllers\Pemutu\DashboardController;
use App\Http\Controllers\Pemutu\DokumenApprovalController;
use App\Http\Controllers\Pemutu\DokumenController;
use App\Http\Controllers\Pemutu\DokumenSpmiController;
use App\Http\Controllers\Pemutu\IndikatorController;
use App\Http\Controllers\Pemutu\IndikatorSummaryController;
use App\Http\Controllers\Pemutu\LabelController;
use App\Http\Controllers\Pemutu\LabelTypeController;
use App\Http\Controllers\Pemutu\PegawaiController;
use App\Http\Controllers\Pemutu\PeriodeKpiController;
use App\Http\Controllers\Pemutu\PeriodeSpmiController;
use App\Http\Controllers\Pemutu\RenopController;
use App\Http\Controllers\Pemutu\StandarController;
use App\Http\Controllers\Pemutu\TimMutuController;
use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 SPMI (PEMTU) Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('pemutu')->name('pemutu.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Periode KPI
    Route::get('periode-kpi/data', [PeriodeKpiController::class, 'data'])->name('periode-kpi.data');
    Route::post('periode-kpi/{periodeKpi}/activate', [PeriodeKpiController::class, 'activate'])->name('periode-kpi.activate');
    Route::resource('periode-kpi', PeriodeKpiController::class);

    // Label Type (modal forms only - no index page)
    Route::resource('label-type', LabelTypeController::class)->except(['index', 'show']);

    // Label
    Route::get('api/label', [LabelController::class, 'data'])->name('label.data');
    Route::resource('label', LabelController::class);

    // Pegawai
    Route::get('api/pegawai', [PegawaiController::class, 'data'])->name('pegawai.data');
    Route::match(['get', 'post'], 'pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::resource('pegawai', PegawaiController::class);

    // Dokumen & Structure (REFACTORED WORKSPACE)
    Route::post('dokumen/reorder', [DokumenController::class, 'reorder'])->name('dokumen.reorder');
    Route::get('dokumen-spmi', [DokumenSpmiController::class, 'index'])->name('dokumen.index');
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

    // Indikator
    Route::get('api/indikator', [IndikatorController::class, 'data'])->name('indikator.data');
    Route::resource('indikator', IndikatorController::class);

    // Indikator Summary (NEW - with separate routes for Standar and Performa)
    Route::get('indikator-summary', [IndikatorSummaryController::class, 'index'])->name('indikator-summary.index');

    // Indikator Standar (ED, AMI, Pengendalian)
    Route::get('indikator-summary/standar', [IndikatorSummaryController::class, 'standar'])->name('indikator-summary.standar');
    Route::get('indikator-summary/standar/data', [IndikatorSummaryController::class, 'dataStandar'])->name('indikator-summary.data-standar');
    Route::get('indikator-summary/standar/count', [IndikatorSummaryController::class, 'summaryCount'])->name('indikator-summary.summary-count');

    // Indikator Performa (KPI)
    Route::get('indikator-summary/performa', [IndikatorSummaryController::class, 'performa'])->name('indikator-summary.performa');
    Route::get('indikator-summary/performa/data', [IndikatorSummaryController::class, 'dataPerforma'])->name('indikator-summary.data-performa');

    // Shared routes
    Route::get('indikator-summary/export', [IndikatorSummaryController::class, 'export'])->name('indikator-summary.export');
    Route::get('indikator-summary/{indikator}', [IndikatorSummaryController::class, 'detail'])->name('indikator-summary.detail');

    // Standar (Indikator Standar)
    Route::get('api/standar', [StandarController::class, 'data'])->name('standar.data');
    // ...
    Route::get('standar/{id}/assign', [App\Http\Controllers\Pemutu\StandarController::class, 'assign'])->name('standar.assign');
    Route::post('standar/{id}/assign', [App\Http\Controllers\Pemutu\StandarController::class, 'storeAssignment'])->name('standar.assign.store');
    Route::resource('standar', App\Http\Controllers\Pemutu\StandarController::class);

    // Document Approvals
    Route::get('dokumen/{dokumen}/approve', [DokumenApprovalController::class, 'create'])->name('dokumen.approve.create');
    // ...
    Route::post('dokumen/{dokumen}/approve', [DokumenApprovalController::class, 'store'])->name('dokumen.approve');
    Route::delete('dokumen/approval/{approval}', [DokumenApprovalController::class, 'destroy'])->name('dokumen.approval.destroy');

    // Period SPMI (PEPP Cycle)
    Route::get('api/periode-spmi', [PeriodeSpmiController::class, 'data'])->name('periode-spmi.data');
    Route::resource('periode-spmi', PeriodeSpmiController::class);

    // Tim Mutu
    Route::get('tim-mutu', [TimMutuController::class, 'index'])->name('tim-mutu.index');
    Route::get('tim-mutu/search-pegawai', [TimMutuController::class, 'searchPegawai'])->name('tim-mutu.search-pegawai');
    Route::get('tim-mutu/{periode}/manage', [TimMutuController::class, 'manage'])->name('tim-mutu.manage');
    Route::get('tim-mutu/{periode}/unit/{unit}/edit-auditee', [TimMutuController::class, 'editAuditee'])->name('tim-mutu.edit-auditee');
    Route::post('tim-mutu/{periode}/unit/{unit}/auditee', [TimMutuController::class, 'storeAuditee'])->name('tim-mutu.store-auditee');
    Route::get('tim-mutu/{periode}/unit/{unit}/edit-auditor', [TimMutuController::class, 'editAuditor'])->name('tim-mutu.edit-auditor');
    Route::post('tim-mutu/{periode}/unit/{unit}/auditor', [TimMutuController::class, 'storeAuditor'])->name('tim-mutu.store-auditor');

    // Renop (Rencana Operasional)
    Route::get('renop/create', [RenopController::class, 'create'])->name('renop.create');
    Route::post('renop', [RenopController::class, 'store'])->name('renop.store');
    Route::get('renop', [RenopController::class, 'index'])->name('renop.index');

    // Evaluasi Diri
    Route::get('evaluasi-diri', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'index'])->name('evaluasi-diri.index');
    Route::get('evaluasi-diri/{periode}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'show'])->name('evaluasi-diri.show');
    Route::get('evaluasi-diri/{periode}/data', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'data'])->name('evaluasi-diri.data');
    Route::get('evaluasi-diri/download/{id}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'downloadAttachment'])->name('evaluasi-diri.download');
    Route::get('evaluasi-diri/{indikator}/edit', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'edit'])->name('evaluasi-diri.edit');
    Route::post('evaluasi-diri/{indikator}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'update'])->name('evaluasi-diri.update');

    // Evaluasi KPI
    Route::get('evaluasi-kpi', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'index'])->name('evaluasi-kpi.index');
    Route::get('evaluasi-kpi/download/{indikatorPegawai}', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'downloadAttachment'])->name('evaluasi-kpi.download');
    Route::get('evaluasi-kpi/{periode}', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'show'])->name('evaluasi-kpi.show');
    Route::get('evaluasi-kpi/{periode}/data', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'data'])->name('evaluasi-kpi.data');
    Route::get('evaluasi-kpi/{indikatorPegawai}/edit', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'edit'])->name('evaluasi-kpi.edit');
    Route::post('evaluasi-kpi/{indikatorPegawai}', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'update'])->name('evaluasi-kpi.update');

    // AMI (Audit Mutu Internal)
    Route::get('ami', [App\Http\Controllers\Pemutu\AmiController::class, 'index'])->name('ami.index');
    Route::get('ami/{periode}', [App\Http\Controllers\Pemutu\AmiController::class, 'show'])->name('ami.show');
    Route::get('ami/{periode}/data', [App\Http\Controllers\Pemutu\AmiController::class, 'data'])->name('ami.data');
    Route::get('ami/detail/{indOrg}', [App\Http\Controllers\Pemutu\AmiController::class, 'detail'])->name('ami.detail');
    Route::post('ami/detail/{indOrg}/nilai', [App\Http\Controllers\Pemutu\AmiController::class, 'submitNilai'])->name('ami.submit-nilai');

    // Diskusi
    Route::post('diskusi/ami/{indOrg}', [App\Http\Controllers\Pemutu\DiskusiController::class, 'storeAmi'])->name('diskusi.store-ami');
    Route::get('diskusi/download/{diskusi}', [App\Http\Controllers\Pemutu\DiskusiController::class, 'download'])->name('diskusi.download');

    // Pengendalian
    Route::get('pengendalian', [App\Http\Controllers\Pemutu\PengendalianController::class, 'index'])->name('pengendalian.index');
    Route::get('pengendalian/{periode}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'show'])->name('pengendalian.show');
    Route::get('pengendalian/{periode}/data', [App\Http\Controllers\Pemutu\PengendalianController::class, 'data'])->name('pengendalian.data');
    Route::get('pengendalian/modal/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'editModal'])->name('pengendalian.edit-modal');
    Route::post('pengendalian/update/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'update'])->name('pengendalian.update');
    Route::post('pengendalian/matrix/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'updateMatrix'])->name('pengendalian.update-matrix');

    // Pengendalian RTM (Rapat Tinjauan Manajemen)
    Route::get('pengendalian/{periode}/rtm/create', [App\Http\Controllers\Pemutu\PengendalianController::class, 'createRtm'])->name('pengendalian.rtm.create');
    Route::post('pengendalian/{periode}/rtm', [App\Http\Controllers\Pemutu\PengendalianController::class, 'storeRtm'])->name('pengendalian.rtm.store');
    Route::get('pengendalian/{periode}/rtm/{rapat}/edit', [App\Http\Controllers\Pemutu\PengendalianController::class, 'editRtm'])->name('pengendalian.rtm.edit');
    Route::put('pengendalian/{periode}/rtm/{rapat}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'updateRtm'])->name('pengendalian.rtm.update');
});
