<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionHrSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionHrSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionData = [
            // ── DASHBOARD ─────────────────────────────────────────────────────
            ['name' => 'hr.dashboard.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard statistik kepegawaian'],

            // ── MASTER DATA ───────────────────────────────────────────────────
            ['name' => 'hr.master-status-pegawai.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Melihat master status pegawai'],
            ['name' => 'hr.master-status-pegawai.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Menambah status pegawai'],
            ['name' => 'hr.master-status-pegawai.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Mengubah status pegawai'],
            ['name' => 'hr.master-status-pegawai.delete', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Menghapus status pegawai'],
            ['name' => 'hr.jabatan-fungsional.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Melihat jabatan fungsional'],
            ['name' => 'hr.jabatan-fungsional.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Menambah jabatan fungsional'],
            ['name' => 'hr.jabatan-fungsional.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Mengubah jabatan fungsional'],
            ['name' => 'hr.jabatan-fungsional.delete', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Menghapus jabatan fungsional'],
            ['name' => 'hr.jenis-izin.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Melihat jenis izin'],
            ['name' => 'hr.jenis-izin.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Menambah jenis izin'],
            ['name' => 'hr.jenis-shift.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Melihat jenis shift kerja'],
            ['name' => 'hr.jenis-shift.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Master Data', 'description' => 'Menambah jenis shift'],

            // ── PEGAWAI ───────────────────────────────────────────────────────
            ['name' => 'hr.pegawai.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Melihat pegawai di unit sendiri'],
            ['name' => 'hr.pegawai.data', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Mengambil data pegawai (DataTables)'],
            ['name' => 'hr.pegawai.view-all', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Melihat data seluruh pegawai'],
            ['name' => 'hr.pegawai.view-own', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Melihat profil diri sendiri'],
            ['name' => 'hr.pegawai.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Menambah data pegawai baru'],
            ['name' => 'hr.pegawai.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Mengubah detail data pegawai'],
            ['name' => 'hr.pegawai.delete', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Menghapus data pegawai'],
            ['name' => 'hr.pegawai.export', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Mengekspor daftar pegawai'],
            ['name' => 'hr.pegawai.import', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Pegawai', 'description' => 'Mengimpor data pegawai dari file'],

            // ── APPROVAL DATA ─────────────────────────────────────────────────
            ['name' => 'hr.approval.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Approval Data', 'description' => 'Melihat perubahan data yang menunggu persetujuan'],
            ['name' => 'hr.approval.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Approval Data', 'description' => 'Menyetujui atau menolak perubahan data pegawai'],

            // ── PERIZINAN ─────────────────────────────────────────────────────
            ['name' => 'hr.perizinan.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Perizinan', 'description' => 'Melihat daftar pengajuan izin pegawai'],
            ['name' => 'hr.perizinan.data', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Perizinan', 'description' => 'Mengambil data perizinan (DataTables)'],
            ['name' => 'hr.perizinan.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Perizinan', 'description' => 'Mengajukan permohonan izin'],
            ['name' => 'hr.perizinan.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Perizinan', 'description' => 'Menyetujui atau menolak izin'],

            // ── LEMBUR ────────────────────────────────────────────────────────
            ['name' => 'hr.lembur.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Lembur', 'description' => 'Melihat daftar pengajuan lembur'],
            ['name' => 'hr.lembur.data', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Lembur', 'description' => 'Mengambil data lembur (DataTables)'],
            ['name' => 'hr.lembur.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Lembur', 'description' => 'Mengajukan permohonan lembur'],
            ['name' => 'hr.lembur.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Lembur', 'description' => 'Memproses permohonan lembur'],

            // ── INDISIPLINER ──────────────────────────────────────────────────
            ['name' => 'hr.indisipliner.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Indisipliner', 'description' => 'Melihat catatan pelanggaran disiplin'],
            ['name' => 'hr.indisipliner.data', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Indisipliner', 'description' => 'Mengambil data indisipliner (DataTables)'],
            ['name' => 'hr.indisipliner.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Indisipliner', 'description' => 'Mencatat pelanggaran disiplin'],
            ['name' => 'hr.indisipliner.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Indisipliner', 'description' => 'Mengubah catatan pelanggaran'],

            // ── PRESENSI & KEHADIRAN ──────────────────────────────────────────
            ['name' => 'hr.tanggal-libur.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Tanggal Libur', 'description' => 'Melihat daftar hari libur'],
            ['name' => 'hr.tanggal-libur.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Tanggal Libur', 'description' => 'Menambah hari libur'],
            ['name' => 'hr.tanggal-libur.delete', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Tanggal Libur', 'description' => 'Menghapus hari libur'],
            ['name' => 'hr.att-device.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Mesin Presensi', 'description' => 'Melihat daftar mesin presensi'],
            ['name' => 'hr.att-device.create', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Mesin Presensi', 'description' => 'Menambah mesin presensi'],
            ['name' => 'hr.att-device.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Mesin Presensi', 'description' => 'Mengubah data mesin presensi'],
            ['name' => 'hr.presensi.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Presensi Online', 'description' => 'Melihat rekap kehadiran pegawai'],
            ['name' => 'hr.presensi.data', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Presensi Online', 'description' => 'Mengambil data presensi (DataTables)'],
            ['name' => 'hr.presensi.view-own', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Presensi Online', 'description' => 'Melihat riwayat kehadiran pribadi'],
            ['name' => 'hr.presensi.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Presensi Online', 'description' => 'Melakukan koreksi data presensi'],

            // ── PENGGAJIAN ────────────────────────────────────────────────────
            ['name' => 'hr.gaji.view', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Penggajian', 'description' => 'Melihat rekapitulasi gaji pegawai'],
            ['name' => 'hr.gaji.data', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Penggajian', 'description' => 'Mengambil data gaji (DataTables)'],
            ['name' => 'hr.gaji.view-own', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Penggajian', 'description' => 'Melihat slip gaji pribadi'],
            ['name' => 'hr.gaji.update', 'category' => 'HR & Kepegawaian', 'sub_category' => 'Penggajian', 'description' => 'Memproses data penggajian'],
        ];

        foreach ($permissionData as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], [
                'guard_name'   => 'web',
                'category'     => $permission['category'],
                'sub_category' => $permission['sub_category'],
                'description'  => $permission['description'],
            ]);
        }

        $admin = Role::where('name', 'Administrator')->first();
        if ($admin) {
            $admin->givePermissionTo(array_column($permissionData, 'name'));
        }
        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['hr.pegawai.view', 'hr.presensi.view', 'hr.perizinan.view', 'hr.approval.view']);
        }
        $eksekutif = Role::where('name', 'Eksekutif')->first();
        if ($eksekutif) {
            $eksekutif->givePermissionTo(['hr.dashboard.view', 'hr.pegawai.view-all', 'hr.gaji.view']);
        }
    }
}
