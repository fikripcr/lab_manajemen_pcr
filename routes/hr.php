<?php

use App\Http\Controllers\Hr\ApprovalController;
use App\Http\Controllers\Hr\AttDeviceController;
use App\Http\Controllers\Hr\DepartemenController;
use App\Http\Controllers\Hr\IndisiplinerController;
use App\Http\Controllers\Hr\JabatanFungsionalController;
use App\Http\Controllers\Hr\JabatanStrukturalController;
use App\Http\Controllers\Hr\JenisIndisiplinerController;
use App\Http\Controllers\Hr\JenisIzinController;
use App\Http\Controllers\Hr\JenisShiftController;
use App\Http\Controllers\Hr\OrgUnitController;
use App\Http\Controllers\Hr\PegawaiController;
use App\Http\Controllers\Hr\PerizinanController;
use App\Http\Controllers\Hr\PosisiController;
use App\Http\Controllers\Hr\StatusAktifitasController;
use App\Http\Controllers\Hr\StatusPegawaiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('hr')->name('hr.')->group(function () {

                                                                                          // Pegawai Routes
    Route::get('pegawai/data', [PegawaiController::class, 'data'])->name('pegawai.data'); // Ensure this exists if used, but PegawaiController uses index() for json.
    Route::get('pegawai/select2-search', [PegawaiController::class, 'select2Search'])->name('pegawai.select2-search');
    Route::resource('pegawai', PegawaiController::class);

    // OrgUnit Routes (Struktur Organisasi)
    Route::get('org-units/data', [OrgUnitController::class, 'data'])->name('org-units.data');
    Route::post('org-units/{org_unit}/toggle-status', [OrgUnitController::class, 'toggleStatus'])->name('org-units.toggle-status');
    Route::post('org-units/reorder', [OrgUnitController::class, 'reorder'])->name('org-units.reorder');
    Route::resource('org-units', OrgUnitController::class);

    // Global Data Routes
    Route::get('keluarga/data', [\App\Http\Controllers\Hr\KeluargaController::class, 'data'])->name('keluarga.data');
    Route::get('pendidikan/data', [\App\Http\Controllers\Hr\RiwayatPendidikanController::class, 'data'])->name('pendidikan.data');
    Route::get('pengembangan/data', [\App\Http\Controllers\Hr\PengembanganDiriController::class, 'data'])->name('pengembangan.data');
    Route::get('status-pegawai-history/data', [\App\Http\Controllers\Hr\RiwayatStatPegawaiController::class, 'data'])->name('status-pegawai-history.data');
    Route::get('status-aktifitas-history/data', [\App\Http\Controllers\Hr\RiwayatStatAktifitasController::class, 'data'])->name('status-aktifitas-history.data');
    Route::get('jabatan-fungsional-history/data', [\App\Http\Controllers\Hr\RiwayatJabFungsionalController::class, 'data'])->name('jabatan-fungsional-history.data');
    Route::get('jabatan-struktural-history/data', [\App\Http\Controllers\Hr\RiwayatJabStrukturalController::class, 'data'])->name('jabatan-struktural-history.data');

    // Global Tab View Routes (Server-Side Redirects)
    Route::get('keluarga', [\App\Http\Controllers\Hr\KeluargaController::class, 'index'])->name('keluarga.index');
    Route::get('pendidikan', [\App\Http\Controllers\Hr\RiwayatPendidikanController::class, 'index'])->name('pendidikan.index');
    Route::get('status-pegawai', [\App\Http\Controllers\Hr\RiwayatStatPegawaiController::class, 'index'])->name('status-pegawai.index');
    Route::get('status-aktifitas', [\App\Http\Controllers\Hr\RiwayatStatAktifitasController::class, 'index'])->name('status-aktifitas.index');
    Route::get('jabatan-fungsional', [\App\Http\Controllers\Hr\RiwayatJabFungsionalController::class, 'index'])->name('jabatan-fungsional.index');
    Route::get('jabatan-struktural', [\App\Http\Controllers\Hr\RiwayatJabStrukturalController::class, 'index'])->name('jabatan-struktural.index');
    Route::get('pengembangan', [\App\Http\Controllers\Hr\PengembanganDiriController::class, 'index'])->name('pengembangan.index');
    Route::get('penugasan', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'index'])->name('penugasan.index');
    Route::get('inpassing', [\App\Http\Controllers\Hr\RiwayatInpassingController::class, 'index'])->name('inpassing.index');
    Route::get('inpassing/data', [\App\Http\Controllers\Hr\RiwayatInpassingController::class, 'data'])->name('inpassing.data');

    // Nested Resources for Pegawai (History & Approval Workflow)
    Route::prefix('pegawai/{pegawai}')->name('pegawai.')->group(function () {
        // Multi-Value Lists (Add New)
        // Multi-Value Lists (Add New)
        Route::resource('keluarga', \App\Http\Controllers\Hr\KeluargaController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('pendidikan', \App\Http\Controllers\Hr\RiwayatPendidikanController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('pengembangan', \App\Http\Controllers\Hr\PengembanganDiriController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('inpassing', \App\Http\Controllers\Hr\RiwayatInpassingController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Single-Value State Changes (Request Change) - These are "Riwayat" lists too, so they need index if we tab them.
        // Status Pegawai
        Route::get('status-pegawai', [\App\Http\Controllers\Hr\RiwayatStatPegawaiController::class, 'index'])->name('status-pegawai.index');
        Route::get('status-pegawai/change', [\App\Http\Controllers\Hr\RiwayatStatPegawaiController::class, 'create'])->name('status-pegawai.create');
        Route::post('status-pegawai/change', [\App\Http\Controllers\Hr\RiwayatStatPegawaiController::class, 'store'])->name('status-pegawai.store');

        // Status Aktifitas
        Route::get('status-aktifitas', [\App\Http\Controllers\Hr\RiwayatStatAktifitasController::class, 'index'])->name('status-aktifitas.index');
        Route::get('status-aktifitas/change', [\App\Http\Controllers\Hr\RiwayatStatAktifitasController::class, 'create'])->name('status-aktifitas.create');
        Route::post('status-aktifitas/change', [\App\Http\Controllers\Hr\RiwayatStatAktifitasController::class, 'store'])->name('status-aktifitas.store');

        // Jabatan Fungsional
        Route::get('jabatan-fungsional', [\App\Http\Controllers\Hr\RiwayatJabFungsionalController::class, 'index'])->name('jabatan-fungsional.index');
        Route::get('jabatan-fungsional/change', [\App\Http\Controllers\Hr\RiwayatJabFungsionalController::class, 'create'])->name('jabatan-fungsional.create');
        Route::post('jabatan-fungsional/change', [\App\Http\Controllers\Hr\RiwayatJabFungsionalController::class, 'store'])->name('jabatan-fungsional.store');

        // Jabatan Struktural (Replacing with Penugasan Index? Or keeping custom view?)
        // User said "Struktural harusnya ke riwayat penugasan".
        // So I will point "jabatan-struktural" tab to RiwayatPenugasanController::index ?
        // Or should I just route it to penugasan.index?
        // Let's create a route for 'penugasan' index first.
        Route::get('penugasan', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'index'])->name('penugasan.index');

        // Jabatan Struktural legacy/specific route if needed, otherwise we rely on Penugasan.
        // I will keep the change routes but maybe Redirect index?
        Route::get('jabatan-struktural/change', [\App\Http\Controllers\Hr\RiwayatJabStrukturalController::class, 'create'])->name('jabatan-struktural.create');
        Route::post('jabatan-struktural/change', [\App\Http\Controllers\Hr\RiwayatJabStrukturalController::class, 'store'])->name('jabatan-struktural.store');

        // Penugasan (Kepala Prodi, Wadir, Direktur, etc.)
        Route::get('penugasan/create', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'create'])->name('penugasan.create');
        Route::post('penugasan', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'store'])->name('penugasan.store');
        Route::get('penugasan/{penugasan}/edit', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'edit'])->name('penugasan.edit');
        Route::put('penugasan/{penugasan}', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'update'])->name('penugasan.update');
        Route::delete('penugasan/{penugasan}', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'destroy'])->name('penugasan.destroy');
        Route::post('penugasan/{penugasan}/end', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'endAssignment'])->name('penugasan.end');
    });

    // Mass Penugasan Routes
    Route::get('pegawai/mass-penugasan', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'massIndex'])->name('pegawai.mass-penugasan.index');
    Route::get('pegawai/mass-penugasan/{unit}', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'massDetail'])->name('pegawai.mass-penugasan.detail');
    Route::post('pegawai/mass-penugasan/assign', [\App\Http\Controllers\Hr\RiwayatPenugasanController::class, 'massAssign'])->name('pegawai.mass-penugasan.assign');

    // Departemen Routes
    Route::get('departemen/data', [DepartemenController::class, 'data'])->name('departemen.data');
    Route::resource('departemen', DepartemenController::class);

    // Posisi Routes
    Route::get('posisi/data', [PosisiController::class, 'data'])->name('posisi.data');
    Route::resource('posisi', PosisiController::class);

    // Tanggal Libur (Holidays)
    Route::resource('tanggal-libur', \App\Http\Controllers\Hr\TanggalLiburController::class);

    // Fallback or Dashboard for HR?
    Route::get('/', function () {
        return redirect()->route('hr.pegawai.index');
    })->name('index');

    // Jabatan Fungsional
    Route::get('jabatan-fungsional/data', [JabatanFungsionalController::class, 'data'])->name('jabatan-fungsional.data');
    Route::resource('jabatan-fungsional', JabatanFungsionalController::class);

    // Jabatan Struktural
    Route::get('jabatan-struktural/data', [JabatanStrukturalController::class, 'data'])->name('jabatan-struktural.data');
    Route::resource('jabatan-struktural', JabatanStrukturalController::class);

    // Status Aktifitas
    Route::get('status-aktifitas/data', [StatusAktifitasController::class, 'data'])->name('status-aktifitas.data');
    Route::resource('status-aktifitas', StatusAktifitasController::class);

    // Status Pegawai
    Route::get('status-pegawai/data', [StatusPegawaiController::class, 'data'])->name('status-pegawai.data');
    Route::resource('status-pegawai', StatusPegawaiController::class);

    // Jenis Shift
    Route::get('jenis-shift/data', [JenisShiftController::class, 'data'])->name('jenis-shift.data');
    Route::resource('jenis-shift', JenisShiftController::class);

    // Mesin Presensi (AttDevice)
    Route::get('att-device/data', [AttDeviceController::class, 'data'])->name('att-device.data');
    Route::resource('att-device', AttDeviceController::class);

    // Indisipliner (Pelanggaran Disiplin)
    Route::get('indisipliner/data', [IndisiplinerController::class, 'data'])->name('indisipliner.data');
    Route::resource('indisipliner', IndisiplinerController::class);

    // Jenis Indisipliner (Master Data Tipe Pelanggaran)
    Route::get('jenis-indisipliner/data', [JenisIndisiplinerController::class, 'data'])->name('jenis-indisipliner.data');
    Route::resource('jenis-indisipliner', JenisIndisiplinerController::class);

    // Perizinan
    Route::get('perizinan/data', [PerizinanController::class, 'data'])->name('perizinan.data');
    Route::resource('perizinan', PerizinanController::class);

    // Jenis Izin
    Route::get('jenis-izin/data', [JenisIzinController::class, 'data'])->name('jenis-izin.data');
    Route::resource('jenis-izin', JenisIzinController::class);

    // Approval Management
    Route::get('approval', [ApprovalController::class, 'index'])->name('approval.index');
    Route::post('approval/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('approval/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
});
