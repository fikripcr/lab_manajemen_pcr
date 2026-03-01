<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionPemutuSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionPemutuSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Module Specific Roles
        Role::firstOrCreate(['name' => 'Auditor Internal']);
        Role::firstOrCreate(['name' => 'Auditor Eksternal']);

        $permissionData = [

            // ── 1. LAPORAN (Dashboard) ─────────────────────────────────────────
            ['name' => 'pemutu.dashboard.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Laporan', 'description' => 'Melihat dashboard capaian mutu SPMI'],

            // ── 2. PENGATURAN ── Label & Kategori ─────────────────────────────
            ['name' => 'pemutu.labels.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Label & Kategori', 'description' => 'Melihat daftar label & kategori indikator'],
            ['name' => 'pemutu.labels.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Label & Kategori', 'description' => 'Mengambil data label (DataTables)'],
            ['name' => 'pemutu.labels.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Label & Kategori', 'description' => 'Menambah label baru'],
            ['name' => 'pemutu.labels.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Label & Kategori', 'description' => 'Mengubah detail label'],
            ['name' => 'pemutu.labels.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Label & Kategori', 'description' => 'Menghapus label'],

            // ── 2. PENGATURAN ── Periode SPMI ─────────────────────────────────
            ['name' => 'pemutu.periode.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode SPMI', 'description' => 'Melihat daftar periode SPMI'],
            ['name' => 'pemutu.periode.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode SPMI', 'description' => 'Mengambil data periode SPMI (DataTables)'],
            ['name' => 'pemutu.periode.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode SPMI', 'description' => 'Menambah periode SPMI baru'],
            ['name' => 'pemutu.periode.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode SPMI', 'description' => 'Mengubah detail periode SPMI'],
            ['name' => 'pemutu.periode.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode SPMI', 'description' => 'Menghapus periode SPMI'],

            // ── 2. PENGATURAN ── Periode KPI ──────────────────────────────────
            ['name' => 'pemutu.periode-kpi.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode KPI', 'description' => 'Melihat daftar periode KPI'],
            ['name' => 'pemutu.periode-kpi.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode KPI', 'description' => 'Mengambil data periode KPI (DataTables)'],
            ['name' => 'pemutu.periode-kpi.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode KPI', 'description' => 'Menambah periode KPI baru'],
            ['name' => 'pemutu.periode-kpi.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode KPI', 'description' => 'Mengubah detail periode KPI'],
            ['name' => 'pemutu.periode-kpi.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Periode KPI', 'description' => 'Menghapus periode KPI'],

            // ── 2. PENGATURAN ── Tim Mutu ─────────────────────────────────────
            ['name' => 'pemutu.tim-mutu.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Tim Mutu', 'description' => 'Melihat daftar personel tim mutu'],
            ['name' => 'pemutu.tim-mutu.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Tim Mutu', 'description' => 'Mengambil data tim mutu (DataTables)'],
            ['name' => 'pemutu.tim-mutu.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Tim Mutu', 'description' => 'Menambah personel baru ke tim mutu'],
            ['name' => 'pemutu.tim-mutu.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Tim Mutu', 'description' => 'Mengubah peran personel tim'],
            ['name' => 'pemutu.tim-mutu.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Tim Mutu', 'description' => 'Menghapus personel dari tim'],

            // ── 3. PENETAPAN ── Kebijakan & Dokumen SPMI ──────────────────────
            ['name' => 'pemutu.dokumen.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Kebijakan', 'description' => 'Melihat daftar dokumen kebijakan mutu'],
            ['name' => 'pemutu.dokumen.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Kebijakan', 'description' => 'Mengambil data dokumen kebijakan (DataTables)'],
            ['name' => 'pemutu.dokumen.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Kebijakan', 'description' => 'Mengunggah dokumen kebijakan baru'],
            ['name' => 'pemutu.dokumen.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Kebijakan', 'description' => 'Mengubah detail dokumen kebijakan'],
            ['name' => 'pemutu.dokumen.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Kebijakan', 'description' => 'Menghapus dokumen kebijakan'],
            ['name' => 'pemutu.dokumen.approve', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Kebijakan', 'description' => 'Menyetujui dokumen kebijakan'],

            // ── 3. PENETAPAN ── Standar ───────────────────────────────────────
            ['name' => 'pemutu.standar.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Melihat daftar standar mutu'],
            ['name' => 'pemutu.standar.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Mengambil data standar mutu (DataTables)'],
            ['name' => 'pemutu.standar.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Menambah standar mutu baru'],
            ['name' => 'pemutu.standar.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Mengubah detail standar mutu'],
            ['name' => 'pemutu.standar.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Menghapus standar mutu'],
            ['name' => 'pemutu.standar.assign', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Memploting standar ke unit terkait'],

            // ── 3. PENETAPAN ── Indikator ─────────────────────────────────────
            ['name' => 'pemutu.indikator.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Melihat daftar indikator keberhasilan'],
            ['name' => 'pemutu.indikator.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Mengambil data indikator (DataTables)'],
            ['name' => 'pemutu.indikator.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Menambah indikator baru'],
            ['name' => 'pemutu.indikator.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Mengubah detail indikator'],
            ['name' => 'pemutu.indikator.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Menghapus indikator'],

            // ── 4. EVALUASI ── Evaluasi Diri ──────────────────────────────────
            ['name' => 'pemutu.evaluasi-diri.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi Diri', 'description' => 'Melihat borang evaluasi diri'],
            ['name' => 'pemutu.evaluasi-diri.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi Diri', 'description' => 'Mengambil data evaluasi diri (DataTables)'],
            ['name' => 'pemutu.evaluasi-diri.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi Diri', 'description' => 'Mengisi atau memperbarui evaluasi diri'],

            // ── 4. EVALUASI ── Evaluasi KPI ───────────────────────────────────
            ['name' => 'pemutu.evaluasi-kpi.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi KPI', 'description' => 'Melihat capaian KPI unit'],
            ['name' => 'pemutu.evaluasi-kpi.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi KPI', 'description' => 'Mengambil data capaian KPI (DataTables)'],
            ['name' => 'pemutu.evaluasi-kpi.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi KPI', 'description' => 'Memperbarui data capaian KPI'],

            // ── 4. EVALUASI ── Audit Mutu Internal (AMI) ──────────────────────
            ['name' => 'pemutu.ami.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Audit Mutu Internal', 'description' => 'Melihat hasil audit mutu internal'],
            ['name' => 'pemutu.ami.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Audit Mutu Internal', 'description' => 'Mengambil data AMI (DataTables)'],
            ['name' => 'pemutu.ami.view-all', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Audit Mutu Internal', 'description' => 'Melihat seluruh hasil AMI semua unit'],
            ['name' => 'pemutu.ami.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Audit Mutu Internal', 'description' => 'Mengisi lembar temuan audit'],

            // ── 5. PENGENDALIAN ───────────────────────────────────────────────
            ['name' => 'pemutu.pengendalian.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Melihat status pengendalian temuan'],
            ['name' => 'pemutu.pengendalian.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Mengambil data pengendalian (DataTables)'],
            ['name' => 'pemutu.pengendalian.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Memproses tindak lanjut pengendalian'],

            // ── 6. SUMMARY INDIKATOR ──────────────────────────────────────────
            ['name' => 'pemutu.indikator-summary.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Summary Indikator', 'description' => 'Melihat ringkasan indikator standar & performa'],
            ['name' => 'pemutu.indikator-summary.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Summary Indikator', 'description' => 'Mengambil data summary indikator (DataTables)'],

            // ── 7. PELAPORAN ──────────────────────────────────────────────────
            ['name' => 'pemutu.export', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pelaporan', 'description' => 'Mengekspor laporan capaian mutu ke file'],
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

        $eksekutif = Role::where('name', 'Eksekutif')->first();
        if ($eksekutif) {
            $eksekutif->givePermissionTo(['pemutu.dashboard.view', 'pemutu.ami.view-all', 'pemutu.indikator-summary.view']);
        }

        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['pemutu.dashboard.view', 'pemutu.evaluasi-diri.view', 'pemutu.evaluasi-kpi.view', 'pemutu.ami.view', 'pemutu.pengendalian.view']);
        }

        $auditorInternal = Role::where('name', 'Auditor Internal')->first();
        if ($auditorInternal) {
            $auditorInternal->givePermissionTo(['pemutu.ami.view', 'pemutu.ami.update', 'pemutu.ami.view-all']);
        }
    }
}
