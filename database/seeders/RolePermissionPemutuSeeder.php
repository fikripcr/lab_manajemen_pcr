<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionPemutuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('RolePermissionPemutuSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Module Specific Roles
        Role::firstOrCreate(['name' => 'Auditor Internal']);
        Role::firstOrCreate(['name' => 'Auditor Eksternal']);

        // Pemutu (SPMI) Permissions (Generalized)
        $permissionData = [
            ['name' => 'pemutu.dashboard.view', 'category' => 'Pemutu', 'sub_category' => 'Laporan', 'description' => 'Melihat dashboard capaian mutu SPMI'],

            ['name' => 'pemutu.periode.view', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Melihat daftar periode audit mutu'],
            ['name' => 'pemutu.periode.data', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengambil data periode (DataTables)'],
            ['name' => 'pemutu.periode.create', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menambah periode audit baru'],
            ['name' => 'pemutu.periode.update', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengubah detail periode'],
            ['name' => 'pemutu.periode.delete', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menghapus data periode'],

            ['name' => 'pemutu.standar.view', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Melihat daftar kriteria standar mutu'],
            ['name' => 'pemutu.standar.data', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengambil data kriteria standar (DataTables)'],
            ['name' => 'pemutu.standar.create', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menambah kriteria standar baru'],
            ['name' => 'pemutu.standar.update', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengubah detail kriteria standar'],
            ['name' => 'pemutu.standar.delete', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menghapus kriteria standar'],
            ['name' => 'pemutu.standar.assign', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Memploting standar ke unit terkait'],

            ['name' => 'pemutu.indikator.view', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Melihat daftar indikator keberhasilan'],
            ['name' => 'pemutu.indikator.data', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengambil data indikator (DataTables)'],
            ['name' => 'pemutu.indikator.create', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menambah indikator baru'],
            ['name' => 'pemutu.indikator.update', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengubah detail indikator'],
            ['name' => 'pemutu.indikator.delete', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menghapus data indikator'],

            ['name' => 'pemutu.dokumen.view', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Melihat daftar dokumen kebijakan mutu'],
            ['name' => 'pemutu.dokumen.data', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengambil data dokumen kebijakan (DataTables)'],
            ['name' => 'pemutu.dokumen.create', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengunggah dokumen kebijakan baru'],
            ['name' => 'pemutu.dokumen.update', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengubah detail dokumen kebijakan'],
            ['name' => 'pemutu.dokumen.delete', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menghapus dokumen kebijakan'],
            ['name' => 'pemutu.dokumen.approve', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menyetujui dokumen kebijakan'],

            ['name' => 'pemutu.tim-mutu.view', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Melihat daftar personel tim mutu'],
            ['name' => 'pemutu.tim-mutu.data', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengambil data personel tim mutu (DataTables)'],
            ['name' => 'pemutu.tim-mutu.create', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menambah personel baru ke tim'],
            ['name' => 'pemutu.tim-mutu.update', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Mengubah peran personnel tim'],
            ['name' => 'pemutu.tim-mutu.delete', 'category' => 'Pemutu', 'sub_category' => 'Pengaturan SPMI', 'description' => 'Menghapus personel dari tim'],

            ['name' => 'pemutu.evaluasi-diri.view', 'category' => 'Pemutu', 'sub_category' => 'Pelaksanaan Mutu', 'description' => 'Melihat borang evaluasi diri'],
            ['name' => 'pemutu.evaluasi-diri.data', 'category' => 'Pemutu', 'sub_category' => 'Pelaksanaan Mutu', 'description' => 'Mengambil data evaluasi diri (DataTables)'],
            ['name' => 'pemutu.evaluasi-diri.update', 'category' => 'Pemutu', 'sub_category' => 'Pelaksanaan Mutu', 'description' => 'Mengisi atau memperbarui evaluasi diri'],

            ['name' => 'pemutu.evaluasi-kpi.view', 'category' => 'Pemutu', 'sub_category' => 'Pelaksanaan Mutu', 'description' => 'Melihat capaian KPI unit'],
            ['name' => 'pemutu.evaluasi-kpi.data', 'category' => 'Pemutu', 'sub_category' => 'Pelaksanaan Mutu', 'description' => 'Mengambil data capaian KPI (DataTables)'],
            ['name' => 'pemutu.evaluasi-kpi.update', 'category' => 'Pemutu', 'sub_category' => 'Pelaksanaan Mutu', 'description' => 'Memperbarui data capaian KPI'],

            ['name' => 'pemutu.ami.view', 'category' => 'Pemutu', 'sub_category' => 'Audit & Pengendalian', 'description' => 'Melihat hasil audit mutu internal'],
            ['name' => 'pemutu.ami.data', 'category' => 'Pemutu', 'sub_category' => 'Audit & Pengendalian', 'description' => 'Mengambil data hasil AMI (DataTables)'],
            ['name' => 'pemutu.ami.view-all', 'category' => 'Pemutu', 'sub_category' => 'Audit & Pengendalian', 'description' => 'Melihat seluruh hasil AMI semua unit'],
            ['name' => 'pemutu.ami.update', 'category' => 'Pemutu', 'sub_category' => 'Audit & Pengendalian', 'description' => 'Mengisi lembar temuan audit'],

            ['name' => 'pemutu.pengendalian.view', 'category' => 'Pemutu', 'sub_category' => 'Audit & Pengendalian', 'description' => 'Melihat status pengendalian temuan'],
            ['name' => 'pemutu.pengendalian.data', 'category' => 'Pemutu', 'sub_category' => 'Audit & Pengendalian', 'description' => 'Mengambil data pengendalian (DataTables)'],
            ['name' => 'pemutu.pengendalian.update', 'category' => 'Pemutu', 'sub_category' => 'Audit & Pengendalian', 'description' => 'Memproses tindak lanjut pengendalian'],

            ['name' => 'pemutu.export', 'category' => 'Pemutu', 'sub_category' => 'Pelaporan', 'description' => 'Mengekspor laporan capaian mutu ke file'],
        ];

        foreach ($permissionData as $permission) {
            Permission::updateOrCreate([
                'name' => $permission['name'],
            ], [
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

        $eksekutif = Role::where('name', 'Eksekutif')->first();
        if ($eksekutif) {
            $eksekutif->givePermissionTo(['pemutu.dashboard.view', 'pemutu.ami.view-all']);
        }

        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['pemutu.dashboard.view', 'pemutu.evaluasi-diri.view', 'pemutu.ami.view']);
        }
    }
}
