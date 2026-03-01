<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionPmbSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionPmbSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'Calon Mahasiswa']);

        $permissionData = [
            // ── DASHBOARD ─────────────────────────────────────────────────────
            ['name' => 'pmb.dashboard.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard statistik pendaftaran'],
            ['name' => 'pmb.dashboard.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Dashboard', 'description' => 'Mengambil data dashboard (DataTables)'],

            // ── MASTER DATA ── Periode ─────────────────────────────────────────
            ['name' => 'pmb.periode.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Periode', 'description' => 'Melihat daftar periode pendaftaran'],
            ['name' => 'pmb.periode.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Periode', 'description' => 'Mengambil data periode (DataTables)'],
            ['name' => 'pmb.periode.create', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Periode', 'description' => 'Menambah periode pendaftaran'],
            ['name' => 'pmb.periode.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Periode', 'description' => 'Mengubah periode pendaftaran'],
            ['name' => 'pmb.periode.delete', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Periode', 'description' => 'Menghapus periode pendaftaran'],

            // ── MASTER DATA ── Jalur Pendaftaran ─────────────────────────────
            ['name' => 'pmb.jalur.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jalur Pendaftaran', 'description' => 'Melihat daftar jalur pendaftaran'],
            ['name' => 'pmb.jalur.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jalur Pendaftaran', 'description' => 'Mengambil data jalur (DataTables)'],
            ['name' => 'pmb.jalur.create', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jalur Pendaftaran', 'description' => 'Menambah jalur pendaftaran'],
            ['name' => 'pmb.jalur.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jalur Pendaftaran', 'description' => 'Mengubah jalur pendaftaran'],
            ['name' => 'pmb.jalur.delete', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jalur Pendaftaran', 'description' => 'Menghapus jalur pendaftaran'],

            // ── MASTER DATA ── Jenis Dokumen ──────────────────────────────────
            ['name' => 'pmb.jenis-dokumen.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jenis Dokumen', 'description' => 'Melihat daftar jenis dokumen syarat'],
            ['name' => 'pmb.jenis-dokumen.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jenis Dokumen', 'description' => 'Mengambil data jenis dokumen (DataTables)'],
            ['name' => 'pmb.jenis-dokumen.create', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jenis Dokumen', 'description' => 'Menambah jenis dokumen'],
            ['name' => 'pmb.jenis-dokumen.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jenis Dokumen', 'description' => 'Mengubah jenis dokumen'],
            ['name' => 'pmb.jenis-dokumen.delete', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Master Data – Jenis Dokumen', 'description' => 'Menghapus jenis dokumen'],

            // ── CALON MAHASISWA BARU (Camaba) ─────────────────────────────────
            ['name' => 'pmb.camaba.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Calon Mahasiswa Baru', 'description' => 'Melihat daftar profil camaba'],
            ['name' => 'pmb.camaba.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Calon Mahasiswa Baru', 'description' => 'Mengambil data camaba (DataTables)'],
            ['name' => 'pmb.camaba.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Calon Mahasiswa Baru', 'description' => 'Mengubah profil camaba'],

            // ── PENDAFTAR ─────────────────────────────────────────────────────
            ['name' => 'pmb.pendaftar.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftar', 'description' => 'Melihat daftar calon mahasiswa baru'],
            ['name' => 'pmb.pendaftar.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftar', 'description' => 'Mengambil data pendaftar (DataTables)'],
            ['name' => 'pmb.pendaftar.create', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftar', 'description' => 'Mendaftarkan camaba secara manual'],
            ['name' => 'pmb.pendaftar.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftar', 'description' => 'Mengubah status verifikasi pendaftar'],
            ['name' => 'pmb.pendaftar.delete', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftar', 'description' => 'Menghapus data pendaftar'],
            ['name' => 'pmb.pendaftar.export', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftar', 'description' => 'Mengekspor data pendaftar ke Excel'],

            // ── PENDAFTARAN ───────────────────────────────────────────────────
            ['name' => 'pmb.pendaftaran.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftaran', 'description' => 'Melihat daftar pendaftaran'],
            ['name' => 'pmb.pendaftaran.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftaran', 'description' => 'Mengambil data pendaftaran (DataTables)'],
            ['name' => 'pmb.pendaftaran.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pendaftaran', 'description' => 'Mengubah data pendaftaran'],

            // ── PEMBAYARAN ────────────────────────────────────────────────────
            ['name' => 'pmb.verification.payments.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pembayaran', 'description' => 'Mengambil data verifikasi pembayaran (DataTables)'],
            ['name' => 'pmb.verification.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pembayaran', 'description' => 'Melakukan verifikasi berkas/pembayaran'],

            // ── SESI UJIAN ────────────────────────────────────────────────────
            ['name' => 'pmb.sesi-ujian.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Sesi Ujian', 'description' => 'Melihat jadwal sesi ujian seleksi'],
            ['name' => 'pmb.sesi-ujian.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Sesi Ujian', 'description' => 'Mengambil data sesi ujian (DataTables)'],
            ['name' => 'pmb.sesi-ujian.create', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Sesi Ujian', 'description' => 'Menambah sesi ujian baru'],
            ['name' => 'pmb.sesi-ujian.update', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Sesi Ujian', 'description' => 'Mengubah sesi ujian'],
            ['name' => 'pmb.sesi-ujian.delete', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Sesi Ujian', 'description' => 'Menghapus sesi ujian'],

            // ── PENGUMUMAN KELULUSAN ───────────────────────────────────────────
            ['name' => 'pmb.verification.documents.data', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Verifikasi Berkas', 'description' => 'Mengambil data verifikasi berkas (DataTables)'],
            ['name' => 'pmb.pengumuman.view', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pengumuman Kelulusan', 'description' => 'Melihat status kelulusan peserta'],
            ['name' => 'pmb.pengumuman.create', 'category' => 'Penerimaan (PMB)', 'sub_category' => 'Pengumuman Kelulusan', 'description' => 'Mempublikasikan hasil seleksi'],
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
            $pimpinan->givePermissionTo(['pmb.dashboard.view', 'pmb.pendaftar.view', 'pmb.pengumuman.view']);
        }
    }
}
