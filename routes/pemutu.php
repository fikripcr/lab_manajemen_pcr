<?php

use App\Http\Controllers\Pemutu\ApprovalController;
use App\Http\Controllers\Pemutu\DashboardController;
use App\Http\Controllers\Pemutu\DokumenController;
use App\Http\Controllers\Pemutu\FiveYearSummaryController;
use App\Http\Controllers\Pemutu\SummaryController;
use App\Http\Controllers\Pemutu\IndikatorController;
use App\Http\Controllers\Pemutu\LabelController;
use App\Http\Controllers\Pemutu\PegawaiController;
use App\Http\Controllers\Pemutu\PelaksanaanController;
use App\Http\Controllers\Pemutu\PeriodeKpiController;
use App\Http\Controllers\Pemutu\PeriodeSpmiController;
use App\Http\Controllers\Pemutu\StandarController;
use App\Http\Controllers\Pemutu\TimMutuController;
use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 SPMI (PEMTU) Public Routes
// ==========================
Route::prefix('pemutu')->name('pemutu.')->group(function () {
    Route::get('dokumen/verify/{dokumen}', [\App\Http\Controllers\Pemutu\DokumenController::class, 'verify'])->name('dokumen.verify');
});

// ==========================
// 🔹 SPMI (PEMTU) Routes
// ==========================
Route::middleware(['auth', 'check.expired'])->prefix('pemutu')->name('pemutu.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/hierarchy/{id}', [DashboardController::class, 'hierarchyNode'])->name('dashboard.hierarchy');

    // 5-Year Historical Summary (PPEPP Timeline)
    Route::get('/five-year-summary', [SummaryController::class, 'fiveYear'])->name('summary.five-year');
    Route::get('/five-year-summary/detail/{rootIndikatorId}', [SummaryController::class, 'fiveYearDetail'])->name('summary.five-year-detail');
    Route::get('/five-year-summary/chart-data', [SummaryController::class, 'fiveYearChartData'])->name('summary.five-year-chart');

    // Global Siklus SPMI Year Selector (session)
    Route::post('set-siklus', function (\Illuminate\Http\Request $request) {
        $request->validate(['siklus_tahun' => 'required|integer']);
        session(['siklus_spmi_tahun' => (int) $request->siklus_tahun]);

        return back();
    })->name('set-siklus');

    // Global Kelompok SPMI Selector (session: akademik, non_akademik)
    Route::get('set-kelompok/{kelompok}', function (string $kelompok) {
        if (!in_array($kelompok, ['akademik', 'non_akademik'])) {
            abort(400);
        }
        session(['pemutu_active_kelompok' => $kelompok]);

        if (request()->has('redirect')) {
            return redirect(request('redirect'));
        }

        return back();
    })->name('set-kelompok');

    // Periode KPI
    Route::post('periode-kpi/{periodeKpi}/activate', [PeriodeKpiController::class, 'activate'])->name('periode-kpi.activate');
    Route::resource('periode-kpi', PeriodeKpiController::class);

    // Label Type (modal forms only - no index page)

    // Label
    Route::get('api/label', [LabelController::class, 'data'])->name('label.data');
    Route::resource('label', LabelController::class);

    // Pegawai
    Route::get('api/pegawai', [PegawaiController::class, 'data'])->name('pegawai.data');
    Route::match(['get', 'post'], 'pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::resource('pegawai', PegawaiController::class);

    // Dokumen & Workspace
    Route::post('dokumen/reorder', [DokumenController::class, 'reorder'])->name('dokumen.reorder');
    Route::get('dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::get('dokumen/{type}/{id}', [DokumenController::class, 'show'])->name('dokumen.show');
    Route::get('dokumen/{type}/{id}/edit', [DokumenController::class, 'edit'])->name('dokumen.edit');
    Route::put('dokumen/{type}/{id}', [DokumenController::class, 'update'])->name('dokumen.update');
    Route::delete('dokumen/{type}/{id}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
    Route::get('dokumen/{type}/{id}/children', [DokumenController::class, 'childrenData'])->name('dokumen.children-data');
    Route::post('dokumen/mapping-sync', [DokumenController::class, 'mappingSync'])->name('dokumen.mapping-sync');
    Route::post('dokumen/{type}/{id}/upload-file', [DokumenController::class, 'uploadFile'])->name('dokumen.upload-file');
    Route::delete('dokumen/{type}/{id}/file/{mediaId}', [DokumenController::class, 'deleteFile'])->name('dokumen.delete-file');
    Route::get('dokumen/{type}/{id}/export', [DokumenController::class, 'export'])->name('dokumen.export');
    Route::get('dokumen/summary', [DokumenController::class, 'summary'])->name('dokumen.summary');
    Route::get('dokumen/summary/{type}/{id}', [DokumenController::class, 'summaryData'])->name('dokumen.summary-data');

    // Document Approvals
    Route::get('dokumen/{dokumen}/approve', [DokumenController::class, 'approveCreate'])->name('dokumen.approve.create');
    Route::post('dokumen/{dokumen}/approve', [DokumenController::class, 'approveStore'])->name('dokumen.approve');
    Route::delete('dokumen/approval/{approval}', [DokumenController::class, 'approveDestroy'])->name('dokumen.approval.destroy');
    Route::get('dokumen/verify/{dokumen}', [DokumenController::class, 'verify'])->name('dokumen.verify');

    // Indikator
    Route::get('api/indikator', [IndikatorController::class, 'data'])->name('indikator.data');
    Route::get('api/indikator/search-doksub', [IndikatorController::class, 'searchDoksub'])->name('indikator.search-doksub');
    Route::resource('indikator', IndikatorController::class);

    // Indikator Summary
    Route::prefix('summary')->name('summary.')->group(function () {
        Route::get('/', [SummaryController::class, 'index'])->name('index');
        Route::get('/standar', [SummaryController::class, 'standar'])->name('standar');
        Route::get('/standar/data', [SummaryController::class, 'dataStandar'])->name('data-standar');
        Route::get('/standar/count', [SummaryController::class, 'summaryCount'])->name('summary-count');
        Route::get('/performa', [SummaryController::class, 'performaIndex'])->name('performa');
        Route::get('/performa/data', [SummaryController::class, 'dataPerforma'])->name('data-performa');
        Route::get('/performa/count', [SummaryController::class, 'summaryCountPerforma'])->name('summary-count-performa');
        Route::get('/export', [SummaryController::class, 'export'])->name('export');
        Route::get('/{indikator}', [SummaryController::class, 'detail'])->name('detail');
    });

    // Standar (Indikator Standar)
    Route::get('api/standar', [StandarController::class, 'data'])->name('standar.data');
    // ...
    Route::get('standar/{id}/assign', [App\Http\Controllers\Pemutu\StandarController::class, 'assign'])->name('standar.assign');
    Route::post('standar/{id}/assign', [App\Http\Controllers\Pemutu\StandarController::class, 'storeAssignment'])->name('standar.assign.store');
    Route::resource('standar', App\Http\Controllers\Pemutu\StandarController::class);

    // Pegawai Approval Inbox
    Route::get('approval', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('approval/{id}', [ApprovalController::class, 'show'])->name('approval.show');
    Route::post('approval/{id}/process', [ApprovalController::class, 'process'])->name('approval.process');

    // Period SPMI (PEPP Cycle)
    Route::resource('periode-spmi', PeriodeSpmiController::class);

    // Tim Mutu
    Route::get('tim-mutu', [TimMutuController::class, 'index'])->name('tim-mutu.index');
    Route::get('tim-mutu/search-pegawai', [TimMutuController::class, 'searchPegawai'])->name('tim-mutu.search-pegawai');
    // Route::get('tim-mutu/{periode}/manage', [TimMutuController::class, 'manage'])->name('tim-mutu.manage');
    Route::get('tim-mutu/{periode}/unit/{unit}/edit-auditee', [TimMutuController::class, 'editAuditee'])->name('tim-mutu.edit-auditee');
    Route::post('tim-mutu/{periode}/unit/{unit}/auditee', [TimMutuController::class, 'storeAuditee'])->name('tim-mutu.store-auditee');
    Route::get('tim-mutu/{periode}/unit/{unit}/edit-auditor', [TimMutuController::class, 'editAuditor'])->name('tim-mutu.edit-auditor');
    Route::post('tim-mutu/{periode}/unit/{unit}/auditor', [TimMutuController::class, 'storeAuditor'])->name('tim-mutu.store-auditor');

    // Pemantauan (Shortened URI)
    Route::get('pemantauan', [PelaksanaanController::class, 'pemantauanIndex'])->name('pemantauan.index');
    Route::get('pemantauan/data', [PelaksanaanController::class, 'pemantauanData'])->name('pemantauan.data');
    Route::get('pemantauan/create', [PelaksanaanController::class, 'pemantauanCreate'])->name('pemantauan.create');
    Route::post('pemantauan', [PelaksanaanController::class, 'pemantauanStore'])->name('pemantauan.store');
    Route::get('pemantauan/{rapat}/edit', [PelaksanaanController::class, 'pemantauanEdit'])->name('pemantauan.edit');
    Route::put('pemantauan/{rapat}', [PelaksanaanController::class, 'pemantauanUpdate'])->name('pemantauan.update');

    // Evaluasi Diri
    Route::get('evaluasi-diri', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'index'])->name('evaluasi-diri.index');
    Route::get('evaluasi-diri/{periode}/data', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'data'])->name('evaluasi-diri.data');
    Route::get('evaluasi-diri/{periode}/ptp-data', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'ptpData'])->name('evaluasi-diri.ptp-data');
    Route::post('evaluasi-diri/upload/{id}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'uploadFile'])->name('evaluasi-diri.upload-file');
    Route::delete('evaluasi-diri/delete/{id}/{mediaId}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'deleteFile'])->name('evaluasi-diri.delete-file');
    Route::get('evaluasi-diri/{indikator}/edit', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'edit'])->name('evaluasi-diri.edit');
    Route::post('evaluasi-diri/{indikator}', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'update'])->name('evaluasi-diri.update');
    Route::get('evaluasi-diri/ptp/{indOrg}/edit', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'editPtp'])->name('evaluasi-diri.ptp-edit');
    Route::post('evaluasi-diri/ptp/{indOrg}/update', [App\Http\Controllers\Pemutu\EvaluasiDiriController::class, 'updatePtp'])->name('evaluasi-diri.ptp-update');

    // Evaluasi KPI
    Route::get('evaluasi-kpi', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'index'])->name('evaluasi-kpi.index');
    Route::post('evaluasi-kpi/upload/{indikatorPegawai}', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'uploadFile'])->name('evaluasi-kpi.upload-file');
    Route::delete('evaluasi-kpi/delete/{indikatorPegawai}/{mediaId}', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'deleteFile'])->name('evaluasi-kpi.delete-file');
    Route::get('evaluasi-kpi/{periode}', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'show'])->name('evaluasi-kpi.show');
    Route::get('evaluasi-kpi/{periode}/data', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'data'])->name('evaluasi-kpi.data');
    Route::get('evaluasi-kpi/{indikatorPegawai}/edit', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'edit'])->name('evaluasi-kpi.edit');
    Route::post('evaluasi-kpi/{indikatorPegawai}', [App\Http\Controllers\Pemutu\EvaluasiKpiController::class, 'update'])->name('evaluasi-kpi.update');

    // AMI (Audit Mutu Internal)
    Route::get('ami', [App\Http\Controllers\Pemutu\AmiController::class, 'index'])->name('ami.index');
    Route::get('ami/{periode}/data', [App\Http\Controllers\Pemutu\AmiController::class, 'data'])->name('ami.data');
    Route::get('ami/{periode}/te-data', [App\Http\Controllers\Pemutu\AmiController::class, 'teData'])->name('ami.te-data');
    Route::get('ami/detail/{indOrg}', [App\Http\Controllers\Pemutu\AmiController::class, 'detail'])->name('ami.detail');
    Route::post('ami/detail/{indOrg}/nilai', [App\Http\Controllers\Pemutu\AmiController::class, 'submitNilai'])->name('ami.submit-nilai');
    Route::get('ami/rtp/{indOrg}', [App\Http\Controllers\Pemutu\AmiController::class, 'editRtp'])->name('ami.rtp-edit');
    Route::post('ami/rtp/{indOrg}', [App\Http\Controllers\Pemutu\AmiController::class, 'updateRtp'])->name('ami.rtp-update');
    Route::get('ami/te/{indOrg}', [App\Http\Controllers\Pemutu\AmiController::class, 'editTe'])->name('ami.te-edit');
    Route::post('ami/te/{indOrg}', [App\Http\Controllers\Pemutu\AmiController::class, 'updateTe'])->name('ami.te-update');

    // AMI Export
    Route::get('ami/{periode}/export-ptk', [App\Http\Controllers\Pemutu\AmiController::class, 'exportPtk'])->name('ami.export-ptk');
    Route::get('ami/{periode}/export-temuan-audit', [App\Http\Controllers\Pemutu\AmiController::class, 'exportTemuanAudit'])->name('ami.export-temuan-audit');
    Route::get('ami/{periode}/export-temuan-positif', [App\Http\Controllers\Pemutu\AmiController::class, 'exportTemuanPositif'])->name('ami.export-temuan-positif');

    // Diskusi
    Route::post('diskusi/ami/{indOrg}', [App\Http\Controllers\Pemutu\DiskusiController::class, 'storeAmi'])->name('diskusi.store-ami');

    // Pengendalian
    Route::get('pengendalian', [App\Http\Controllers\Pemutu\PengendalianController::class, 'index'])->name('pengendalian.index');
    Route::get('pengendalian/{periode}/data', [App\Http\Controllers\Pemutu\PengendalianController::class, 'data'])->name('pengendalian.data');
    Route::get('pengendalian/modal/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'editModal'])->name('pengendalian.edit-modal');
    Route::get('pengendalian/validasi-modal/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'validasiModal'])->name('pengendalian.validasi-modal');
    Route::post('pengendalian/update/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'update'])->name('pengendalian.update');
    Route::post('pengendalian/validasi/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'validasi'])->name('pengendalian.validasi');
    Route::post('pengendalian/matrix/{indOrg}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'updateMatrix'])->name('pengendalian.update-matrix');

    // Pengendalian RTM (Rapat Tinjauan Manajemen)
    Route::get('pengendalian/{periode}/rtm/create', [App\Http\Controllers\Pemutu\PengendalianController::class, 'createRtm'])->name('pengendalian.rtm.create');
    Route::post('pengendalian/{periode}/rtm', [App\Http\Controllers\Pemutu\PengendalianController::class, 'storeRtm'])->name('pengendalian.rtm.store');
    Route::get('pengendalian/{periode}/rtm/{rapat}/edit', [App\Http\Controllers\Pemutu\PengendalianController::class, 'editRtm'])->name('pengendalian.rtm.edit');
    Route::put('pengendalian/{periode}/rtm/{rapat}', [App\Http\Controllers\Pemutu\PengendalianController::class, 'updateRtm'])->name('pengendalian.rtm.update');

    // Peningkatan
    Route::get('peningkatan', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'index'])->name('peningkatan.index');

    // Peningkatan RTM (Rapat Tinjauan Manajemen)
    Route::get('peningkatan/{periode}/rtm/create', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'createRtm'])->name('peningkatan.rtm.create');
    Route::post('peningkatan/{periode}/rtm', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'storeRtm'])->name('peningkatan.rtm.store');
    Route::get('peningkatan/{periode}/rtm/{rapat}/edit', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'editRtm'])->name('peningkatan.rtm.edit');
    Route::put('peningkatan/{periode}/rtm/{rapat}', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'updateRtm'])->name('peningkatan.rtm.update');

    // Peningkatan Duplikasi & Review
    Route::get('peningkatan/{periode}/standar-list', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'standarList'])->name('peningkatan.standar-list');
    Route::post('peningkatan/{periode}/duplikasi', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'duplicateStandar'])->name('peningkatan.duplikasi');
    Route::delete('peningkatan/{periode}/standar/{dokumen}', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'deleteStandarTarget'])->name('peningkatan.delete-standar');
    Route::delete('peningkatan/{periode}/standar-bulk', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'deleteStandarTargetBulk'])->name('peningkatan.delete-standar-bulk');
    Route::get('peningkatan/{periode}/review-data', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'reviewData'])->name('peningkatan.review-data');
    Route::get('peningkatan/history/{id}', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'history'])->name('peningkatan.history');
    Route::get('peningkatan/review/{id}/edit', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'editReviewItem'])->name('peningkatan.review-edit');
    Route::put('peningkatan/review/{id}', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'updateReviewItem'])->name('peningkatan.review-update');
    Route::post('peningkatan/{periode}/approve-staging', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'approveStaging'])->name('peningkatan.approve-staging');
    Route::get('peningkatan/{periode}/staging-status', [App\Http\Controllers\Pemutu\PeningkatanController::class, 'getStagingStatus'])->name('peningkatan.staging-status');
});
