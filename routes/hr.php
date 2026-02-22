<?php

use App\Http\Controllers\Hr\ApprovalController;
use App\Http\Controllers\Hr\AttDeviceController;
use App\Http\Controllers\Hr\DashboardController;
use App\Http\Controllers\Hr\IndisiplinerController;
use App\Http\Controllers\Hr\JabatanFungsionalController;
use App\Http\Controllers\Hr\JenisIndisiplinerController;
use App\Http\Controllers\Hr\JenisIzinController;
use App\Http\Controllers\Hr\JenisShiftController;
use App\Http\Controllers\Hr\KeluargaController;
use App\Http\Controllers\Hr\LemburController;
use App\Http\Controllers\Hr\PegawaiController;
use App\Http\Controllers\Hr\PengembanganDiriController;
use App\Http\Controllers\Hr\PerizinanController;
use App\Http\Controllers\Hr\PresensiController;
use App\Http\Controllers\Hr\RiwayatInpassingController;
use App\Http\Controllers\Hr\RiwayatJabFungsionalController;
use App\Http\Controllers\Hr\RiwayatJabStrukturalController;
use App\Http\Controllers\Hr\RiwayatPendidikanController;
use App\Http\Controllers\Hr\RiwayatPenugasanController;
use App\Http\Controllers\Hr\RiwayatStatAktifitasController;
use App\Http\Controllers\Hr\RiwayatStatPegawaiController;
use App\Http\Controllers\Hr\StatusAktifitasController;
use App\Http\Controllers\Hr\StatusPegawaiController;
use App\Http\Controllers\Hr\TanggalLiburController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('hr')->name('hr.')->group(function () {

    // 🔹 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');

    // Redirect root /hr to /hr/dashboard
    Route::get('/', function () {
        return redirect()->route('hr.dashboard');
    });

                                                                                          // Pegawai Routes
    Route::get('pegawai/data', [PegawaiController::class, 'data'])->name('pegawai.data'); // Ensure this exists if used, but PegawaiController uses index() for json.
    Route::get('pegawai/select2-search', [PegawaiController::class, 'select2Search'])->name('pegawai.select2-search');
    Route::get('pegawai/upload-photo', [PresensiController::class, 'showUploadPhoto'])->name('pegawai.upload-photo');
    Route::post('pegawai/upload-photo', [PresensiController::class, 'storeUploadPhoto'])->name('pegawai.upload-photo.store');
    Route::resource('pegawai', PegawaiController::class);

    // OrgUnit Routes (Struktur Organisasi) - Moved to Shared
    // Route::resource('org-units', OrgUnitController::class);

    // Global Data Routes
    Route::get('keluarga/data', [KeluargaController::class, 'data'])->name('keluarga.data');
    Route::get('pendidikan/data', [RiwayatPendidikanController::class, 'data'])->name('pendidikan.data');
    Route::get('pengembangan/data', [PengembanganDiriController::class, 'data'])->name('pengembangan.data');
    Route::get('status-pegawai-history/data', [RiwayatStatPegawaiController::class, 'data'])->name('status-pegawai-history.data');
    Route::get('status-aktifitas-history/data', [RiwayatStatAktifitasController::class, 'data'])->name('status-aktifitas-history.data');
    Route::get('jabatan-fungsional-history/data', [RiwayatJabFungsionalController::class, 'data'])->name('jabatan-fungsional-history.data');
    Route::get('jabatan-struktural-history/data', [RiwayatJabStrukturalController::class, 'data'])->name('jabatan-struktural-history.data');

    // Global Tab View Routes (Server-Side Redirects)
    Route::get('keluarga', [KeluargaController::class, 'index'])->name('keluarga.index');
    Route::get('pendidikan', [RiwayatPendidikanController::class, 'index'])->name('pendidikan.index');
    Route::get('status-pegawai', [RiwayatStatPegawaiController::class, 'index'])->name('status-pegawai.index');
    Route::get('status-aktifitas', [RiwayatStatAktifitasController::class, 'index'])->name('status-aktifitas.index');
    Route::get('jabatan-fungsional', [RiwayatJabFungsionalController::class, 'index'])->name('jabatan-fungsional.index');
    Route::get('jabatan-struktural', [RiwayatJabStrukturalController::class, 'index'])->name('jabatan-struktural.index');
    Route::get('pengembangan', [PengembanganDiriController::class, 'index'])->name('pengembangan.index');
    Route::get('penugasan', [RiwayatPenugasanController::class, 'index'])->name('penugasan.index');
    Route::get('inpassing', [RiwayatInpassingController::class, 'index'])->name('inpassing.index');
    Route::get('inpassing/data', [RiwayatInpassingController::class, 'data'])->name('inpassing.data');

    // Nested Resources for Pegawai (History & Approval Workflow)
    Route::prefix('pegawai/{pegawai}')->name('pegawai.')->group(function () {
        // Multi-Value Lists (Add New)
        // Multi-Value Lists (Add New)
        Route::resource('keluarga', KeluargaController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('pendidikan', RiwayatPendidikanController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('pengembangan', PengembanganDiriController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('inpassing', RiwayatInpassingController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Single-Value State Changes (Request Change) - These are "Riwayat" lists too, so they need index if we tab them.
        // Status Pegawai
        Route::get('status-pegawai', [RiwayatStatPegawaiController::class, 'index'])->name('status-pegawai.index');
        Route::get('status-pegawai/change', [RiwayatStatPegawaiController::class, 'create'])->name('status-pegawai.create');
        Route::post('status-pegawai/change', [RiwayatStatPegawaiController::class, 'store'])->name('status-pegawai.store');

        // Status Aktifitas
        Route::get('status-aktifitas', [RiwayatStatAktifitasController::class, 'index'])->name('status-aktifitas.index');
        Route::get('status-aktifitas/change', [RiwayatStatAktifitasController::class, 'create'])->name('status-aktifitas.create');
        Route::post('status-aktifitas/change', [RiwayatStatAktifitasController::class, 'store'])->name('status-aktifitas.store');

        // Jabatan Fungsional
        Route::get('jabatan-fungsional', [RiwayatJabFungsionalController::class, 'index'])->name('jabatan-fungsional.index');
        Route::get('jabatan-fungsional/change', [RiwayatJabFungsionalController::class, 'create'])->name('jabatan-fungsional.create');
        Route::post('jabatan-fungsional/change', [RiwayatJabFungsionalController::class, 'store'])->name('jabatan-fungsional.store');

        // Jabatan Struktural (Replacing with Penugasan Index? Or keeping custom view?)
        // User said "Struktural harusnya ke riwayat penugasan".
        // So I will point "jabatan-struktural" tab to RiwayatPenugasanController::index ?
        // Or should I just route it to penugasan.index?
        // Let's create a route for 'penugasan' index first.
        Route::get('penugasan', [RiwayatPenugasanController::class, 'index'])->name('penugasan.index');

        // Jabatan Struktural legacy/specific route if needed, otherwise we rely on Penugasan.
        // I will keep the change routes but maybe Redirect index?
        Route::get('jabatan-struktural/change', [RiwayatJabStrukturalController::class, 'create'])->name('jabatan-struktural.create');
        Route::post('jabatan-struktural/change', [RiwayatJabStrukturalController::class, 'store'])->name('jabatan-struktural.store');

        // Penugasan (Kepala Prodi, Wadir, Direktur, etc.)
        Route::get('penugasan/create', [RiwayatPenugasanController::class, 'create'])->name('penugasan.create');
        Route::post('penugasan', [RiwayatPenugasanController::class, 'store'])->name('penugasan.store');
        Route::get('penugasan/{penugasan}/edit', [RiwayatPenugasanController::class, 'edit'])->name('penugasan.edit');
        Route::put('penugasan/{penugasan}', [RiwayatPenugasanController::class, 'update'])->name('penugasan.update');
        Route::delete('penugasan/{penugasan}', [RiwayatPenugasanController::class, 'destroy'])->name('penugasan.destroy');
        Route::post('penugasan/{penugasan}/end', [RiwayatPenugasanController::class, 'endAssignment'])->name('penugasan.end');

        // Riwayat Pengajuan (Approval History)
        Route::get('pengajuan', [ApprovalController::class, 'employeeHistory'])->name('pengajuan.index');

        // File Pegawai
        Route::get('files/data', [FilePegawaiController::class, 'index'])->name('files.data');
        Route::resource('files', FilePegawaiController::class)->only(['store', 'destroy']);
    });

    // Mass Penugasan Routes
    Route::get('pegawai/mass-penugasan', [RiwayatPenugasanController::class, 'massIndex'])->name('pegawai.mass-penugasan.index');
    Route::get('pegawai/mass-penugasan/{unit}', [RiwayatPenugasanController::class, 'massDetail'])->name('pegawai.mass-penugasan.detail');
    Route::post('pegawai/mass-penugasan/assign', [RiwayatPenugasanController::class, 'massAssign'])->name('pegawai.mass-penugasan.assign');

    // Tanggal Libur (Holidays)
    Route::resource('tanggal-libur', TanggalLiburController::class);

    // Fallback or Dashboard for HR? (Removed conflicting redirect, handled at top)
    // Route::get('/', function () {
    //     return redirect()->route('hr.pegawai.index');
    // })->name('index');

    // Jabatan Fungsional
    Route::get('jabatan-fungsional/data', [JabatanFungsionalController::class, 'data'])->name('jabatan-fungsional.data');
    Route::resource('jabatan-fungsional', JabatanFungsionalController::class);

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

    // Lembur (Overtime)
    Route::get('lembur/data', [LemburController::class, 'data'])->name('lembur.data');
    Route::post('lembur/{lembur}/approve', [LemburController::class, 'approve'])->name('lembur.approve');
    Route::resource('lembur', LemburController::class);

    // Jenis Izin
    Route::get('jenis-izin/data', [JenisIzinController::class, 'data'])->name('jenis-izin.data');
    Route::resource('jenis-izin', JenisIzinController::class);

    // Approval Management
    Route::get('approval', [ApprovalController::class, 'index'])->name('approval.index');
    Route::post('approval/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('approval/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');

    // Presensi Routes
    Route::prefix('presensi')->name('hr.presensi.')->group(function () {
        Route::get('/', [PresensiController::class, 'index'])->name('index');
        Route::get('/settings', [PresensiController::class, 'settings'])->name('settings');
        Route::post('/settings', [PresensiController::class, 'updateSettings'])->name('update-settings');
        Route::get('/get-settings', [PresensiController::class, 'getSettings'])->name('get-settings');
        Route::post('/checkin', [PresensiController::class, 'checkIn'])->name('checkin');
        Route::post('/checkout', [PresensiController::class, 'checkOut'])->name('checkout');
        Route::get('/employee-face-data', [PresensiController::class, 'getEmployeeFaceData'])->name('employee-face-data');
        Route::get('/history', [PresensiController::class, 'history'])->name('history');
        Route::post('/history', [PresensiController::class, 'historyData'])->name('history.data');
    });

    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/upload-photo', [PresensiController::class, 'showUploadPhoto'])->name('upload-photo');
        Route::post('/upload-photo', [PresensiController::class, 'storeUploadPhoto'])->name('upload-photo.store');
    });

    Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('presensi/checkin', [PresensiController::class, 'checkIn'])->name('presensi.checkin');
    Route::post('presensi/checkout', [PresensiController::class, 'checkOut'])->name('presensi.checkout');
    Route::get('presensi/get-location', [PresensiController::class, 'getCurrentLocation'])->name('presensi.get-location');
    Route::get('presensi/settings', [PresensiController::class, 'settings'])->name('presensi.settings');
    Route::get('presensi/get-settings', [PresensiController::class, 'getSettings'])->name('presensi.get-settings');
    Route::post('presensi/update-settings', [PresensiController::class, 'updateSettings'])->name('presensi.update-settings');
    Route::get('presensi/history', [PresensiController::class, 'history'])->name('presensi.history');
    Route::get('presensi/history-data', [PresensiController::class, 'historyData'])->name('presensi.history-data');
    Route::get('presensi/employee-face-data', [PresensiController::class, 'getEmployeeFaceData'])->name('presensi.employee-face-data');
});
