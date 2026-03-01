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

        // Create Module Specific Role
        Role::firstOrCreate(['name' => 'Calon Mahasiswa']);

        $permissionData = [
            ['name' => 'pmb.dashboard.view', 'category' => 'PMB', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard statistik pendaftaran'],
            ['name' => 'pmb.dashboard.data', 'category' => 'PMB', 'sub_category' => 'Dashboard', 'description' => 'Mengambil data dashboard (DataTables)'],

            ['name' => 'pmb.pendaftar.view', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Melihat daftar calon mahasiswa baru'],
            ['name' => 'pmb.pendaftar.data', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Mengambil data pendaftar (DataTables)'],
            ['name' => 'pmb.pendaftar.create', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Mendaftarkan calon mahasiswa secara manual'],
            ['name' => 'pmb.pendaftar.update', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Mengubah status verifikasi pendaftar'],
            ['name' => 'pmb.pendaftar.delete', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Menghapus data pendaftar'],
            ['name' => 'pmb.pendaftar.export', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Mengekspor data pendaftar ke Excel'],

            ['name' => 'pmb.pendaftaran.view', 'category' => 'PMB', 'sub_category' => 'Pendaftaran', 'description' => 'Melihat daftar pendaftaran'],
            ['name' => 'pmb.pendaftaran.data', 'category' => 'PMB', 'sub_category' => 'Pendaftaran', 'description' => 'Mengambil data pendaftaran (DataTables)'],
            ['name' => 'pmb.pendaftaran.update', 'category' => 'PMB', 'sub_category' => 'Pendaftaran', 'description' => 'Mengubah data pendaftaran'],

            ['name' => 'pmb.sesi-ujian.view', 'category' => 'PMB', 'sub_category' => 'Sesi Ujian', 'description' => 'Melihat jadwal sesi ujian'],
            ['name' => 'pmb.sesi-ujian.data', 'category' => 'PMB', 'sub_category' => 'Sesi Ujian', 'description' => 'Mengambil data sesi ujian (DataTables)'],
            ['name' => 'pmb.sesi-ujian.create', 'category' => 'PMB', 'sub_category' => 'Sesi Ujian', 'description' => 'Menambah sesi ujian baru'],
            ['name' => 'pmb.sesi-ujian.update', 'category' => 'PMB', 'sub_category' => 'Sesi Ujian', 'description' => 'Mengubah sesi ujian'],
            ['name' => 'pmb.sesi-ujian.delete', 'category' => 'PMB', 'sub_category' => 'Sesi Ujian', 'description' => 'Menghapus sesi ujian'],

            ['name' => 'pmb.periode.view', 'category' => 'PMB', 'sub_category' => 'Periode', 'description' => 'Melihat daftar periode pendaftaran'],
            ['name' => 'pmb.periode.data', 'category' => 'PMB', 'sub_category' => 'Periode', 'description' => 'Mengambil data periode (DataTables)'],
            ['name' => 'pmb.periode.create', 'category' => 'PMB', 'sub_category' => 'Periode', 'description' => 'Menambah periode pendaftaran'],
            ['name' => 'pmb.periode.update', 'category' => 'PMB', 'sub_category' => 'Periode', 'description' => 'Mengubah periode pendaftaran'],
            ['name' => 'pmb.periode.delete', 'category' => 'PMB', 'sub_category' => 'Periode', 'description' => 'Menghapus periode pendaftaran'],

            ['name' => 'pmb.jalur.view', 'category' => 'PMB', 'sub_category' => 'Jalur', 'description' => 'Melihat daftar jalur pendaftaran'],
            ['name' => 'pmb.jalur.data', 'category' => 'PMB', 'sub_category' => 'Jalur', 'description' => 'Mengambil data jalur (DataTables)'],
            ['name' => 'pmb.jalur.create', 'category' => 'PMB', 'sub_category' => 'Jalur', 'description' => 'Menambah jalur pendaftaran'],
            ['name' => 'pmb.jalur.update', 'category' => 'PMB', 'sub_category' => 'Jalur', 'description' => 'Mengubah jalur pendaftaran'],
            ['name' => 'pmb.jalur.delete', 'category' => 'PMB', 'sub_category' => 'Jalur', 'description' => 'Menghapus jalur pendaftaran'],

            ['name' => 'pmb.prodi.view', 'category' => 'PMB', 'sub_category' => 'Program Studi', 'description' => 'Melihat daftar program studi PMB'],
            ['name' => 'pmb.prodi.data', 'category' => 'PMB', 'sub_category' => 'Program Studi', 'description' => 'Mengambil data program studi (DataTables)'],
            ['name' => 'pmb.prodi.create', 'category' => 'PMB', 'sub_category' => 'Program Studi', 'description' => 'Menambah program studi PMB'],
            ['name' => 'pmb.prodi.update', 'category' => 'PMB', 'sub_category' => 'Program Studi', 'description' => 'Mengubah program studi PMB'],
            ['name' => 'pmb.prodi.delete', 'category' => 'PMB', 'sub_category' => 'Program Studi', 'description' => 'Menghapus program studi PMB'],

            ['name' => 'pmb.jenis-dokumen.view', 'category' => 'PMB', 'sub_category' => 'Jenis Dokumen', 'description' => 'Melihat daftar jenis dokumen syarat'],
            ['name' => 'pmb.jenis-dokumen.data', 'category' => 'PMB', 'sub_category' => 'Jenis Dokumen', 'description' => 'Mengambil data jenis dokumen (DataTables)'],
            ['name' => 'pmb.jenis-dokumen.create', 'category' => 'PMB', 'sub_category' => 'Jenis Dokumen', 'description' => 'Menambah jenis dokumen'],
            ['name' => 'pmb.jenis-dokumen.update', 'category' => 'PMB', 'sub_category' => 'Jenis Dokumen', 'description' => 'Mengubah jenis dokumen'],
            ['name' => 'pmb.jenis-dokumen.delete', 'category' => 'PMB', 'sub_category' => 'Jenis Dokumen', 'description' => 'Menghapus jenis dokumen'],

            ['name' => 'pmb.camaba.view', 'category' => 'PMB', 'sub_category' => 'Camaba', 'description' => 'Melihat profil camaba'],
            ['name' => 'pmb.camaba.data', 'category' => 'PMB', 'sub_category' => 'Camaba', 'description' => 'Mengambil data profil camaba (DataTables)'],
            ['name' => 'pmb.camaba.update', 'category' => 'PMB', 'sub_category' => 'Camaba', 'description' => 'Mengubah profil camaba'],

            ['name' => 'pmb.verification.payments.data', 'category' => 'PMB', 'sub_category' => 'Verifikasi', 'description' => 'Mengambil data verifikasi pembayaran (DataTables)'],
            ['name' => 'pmb.verification.documents.data', 'category' => 'PMB', 'sub_category' => 'Verifikasi', 'description' => 'Mengambil data verifikasi berkas (DataTables)'],
            ['name' => 'pmb.verification.update', 'category' => 'PMB', 'sub_category' => 'Verifikasi', 'description' => 'Melakukan verifikasi berkas/pembayaran'],

            ['name' => 'pmb.pengumuman.view', 'category' => 'PMB', 'sub_category' => 'Pengumuman', 'description' => 'Melihat status kelulusan peserta'],
            ['name' => 'pmb.pengumuman.create', 'category' => 'PMB', 'sub_category' => 'Pengumuman', 'description' => 'Mempublikasikan hasil seleksi'],
        ];

        foreach ($permissionData as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], [
                'guard_name'   => 'web',
                'category'     => $permission['category'],
                'sub_category' => $permission['sub_category'],
                'description'  => $permission['description'],
            ]);
        }

        // Global Role Assignment
        $admin = Role::where('name', 'Administrator')->first();
        if ($admin) {
            $admin->givePermissionTo(array_column($permissionData, 'name'));
        }

        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['pmb.dashboard.view', 'pmb.pendaftar.view']);
        }
    }
}
