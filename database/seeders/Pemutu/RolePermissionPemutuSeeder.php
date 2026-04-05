<?php

namespace Database\Seeders\Pemutu;

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

        // ──────────────────────────────────────────────────────────
        // ROLES
        // Note: Administrator, Eksekutif, Pimpinan Unit created in MainSysSeeder
        // ──────────────────────────────────────────────────────────
        Role::firstOrCreate(['name' => 'Pegawai']);
        Role::firstOrCreate(['name' => 'Auditee']);
        Role::firstOrCreate(['name' => 'Auditor Internal']);
        Role::firstOrCreate(['name' => 'Auditor Eksternal']);
        Role::firstOrCreate(['name' => 'Admin SPMI']);
        Role::firstOrCreate(['name' => 'Guest']);

        // ──────────────────────────────────────────────────────────
        // PERMISSIONS
        // ──────────────────────────────────────────────────────────
        $permissionData = [

            // ═══════════════════════════════════════════════════════
            // 1. DASHBOARD & SUMMARY
            // ═══════════════════════════════════════════════════════
            ['name' => 'pemutu.dashboard.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard capaian mutu SPMI'],

            ['name' => 'pemutu.summary.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Summary', 'description' => 'Melihat summary standar, performa, dan histori PPEPP'],

            // ═══════════════════════════════════════════════════════
            // 2. PENGATURAN (MASTER DATA)
            // ═══════════════════════════════════════════════════════

            // Label
            ['name' => 'pemutu.label.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Label', 'description' => 'Melihat daftar label & kategori indikator'],
            ['name' => 'pemutu.label.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Label', 'description' => 'Mengambil data label (DataTables)'],
            ['name' => 'pemutu.label.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Label', 'description' => 'Menambah label baru'],
            ['name' => 'pemutu.label.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Label', 'description' => 'Mengubah detail label'],
            ['name' => 'pemutu.label.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Label', 'description' => 'Menghapus label'],

            // Periode SPMI
            ['name' => 'pemutu.periode-spmi.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode SPMI', 'description' => 'Melihat daftar periode SPMI'],
            ['name' => 'pemutu.periode-spmi.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode SPMI', 'description' => 'Menambah periode SPMI baru'],
            ['name' => 'pemutu.periode-spmi.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode SPMI', 'description' => 'Mengubah detail periode SPMI'],
            ['name' => 'pemutu.periode-spmi.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode SPMI', 'description' => 'Menghapus periode SPMI'],

            // Periode KPI
            ['name' => 'pemutu.periode-kpi.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode KPI', 'description' => 'Melihat daftar periode KPI'],
            ['name' => 'pemutu.periode-kpi.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode KPI', 'description' => 'Menambah periode KPI baru'],
            ['name' => 'pemutu.periode-kpi.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode KPI', 'description' => 'Mengubah detail periode KPI'],
            ['name' => 'pemutu.periode-kpi.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode KPI', 'description' => 'Menghapus periode KPI'],
            ['name' => 'pemutu.periode-kpi.activate', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Periode KPI', 'description' => 'Mengaktifkan periode KPI'],

            // Tim Mutu
            ['name' => 'pemutu.tim-mutu.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Tim Mutu', 'description' => 'Melihat daftar tim mutu per unit'],
            ['name' => 'pemutu.tim-mutu.assign-auditee', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Tim Mutu', 'description' => 'Menetapkan auditee & anggota tim mutu'],
            ['name' => 'pemutu.tim-mutu.assign-auditor', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Tim Mutu', 'description' => 'Menetapkan ketua auditor & auditor'],
            ['name' => 'pemutu.tim-mutu.manage', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Tim Mutu', 'description' => 'Mengelola seluruh tim mutu (admin)'],

            // Data Pegawai (Pemutu context)
            ['name' => 'pemutu.pegawai.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Pegawai', 'description' => 'Melihat daftar pegawai'],
            ['name' => 'pemutu.pegawai.manage', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengaturan – Pegawai', 'description' => 'Mengelola data pegawai (CRUD + import)'],

            // ═══════════════════════════════════════════════════════
            // 3. PENETAPAN (DOKUMEN & INDIKATOR)
            // ═══════════════════════════════════════════════════════

            // Dokumen SPMI
            ['name' => 'pemutu.dokumen.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Dokumen', 'description' => 'Melihat workspace dokumen SPMI'],
            ['name' => 'pemutu.dokumen.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Dokumen', 'description' => 'Membuat dokumen SPMI baru'],
            ['name' => 'pemutu.dokumen.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Dokumen', 'description' => 'Mengubah dokumen SPMI'],
            ['name' => 'pemutu.dokumen.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Dokumen', 'description' => 'Menghapus dokumen SPMI'],
            ['name' => 'pemutu.dokumen.upload', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Dokumen', 'description' => 'Upload file ke dokumen'],

            // Standar
            ['name' => 'pemutu.standar.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Melihat daftar standar mutu'],
            ['name' => 'pemutu.standar.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Mengambil data standar (DataTables)'],
            ['name' => 'pemutu.standar.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Menambah standar mutu baru'],
            ['name' => 'pemutu.standar.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Mengubah detail standar'],
            ['name' => 'pemutu.standar.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Menghapus standar'],
            ['name' => 'pemutu.standar.assign', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Standar', 'description' => 'Menetapkan standar ke unit kerja'],

            // Indikator
            ['name' => 'pemutu.indikator.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Melihat daftar indikator'],
            ['name' => 'pemutu.indikator.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Mengambil data indikator (DataTables)'],
            ['name' => 'pemutu.indikator.create', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Menambah indikator baru'],
            ['name' => 'pemutu.indikator.update', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Mengubah detail indikator'],
            ['name' => 'pemutu.indikator.delete', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Penetapan – Indikator', 'description' => 'Menghapus indikator'],

            // ═══════════════════════════════════════════════════════
            // 4. APPROVAL DOKUMEN
            // ═══════════════════════════════════════════════════════
            ['name' => 'pemutu.approval.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Approval Dokumen', 'description' => 'Melihat inbox approval'],
            ['name' => 'pemutu.approval.process', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Approval Dokumen', 'description' => 'Memproses approval (approve/reject)'],

            // ═══════════════════════════════════════════════════════
            // 5. EVALUASI DIRI & KPI
            // ═══════════════════════════════════════════════════════

            // Evaluasi Diri
            ['name' => 'pemutu.evaluasi-diri.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi Diri', 'description' => 'Melihat borang evaluasi diri'],
            ['name' => 'pemutu.evaluasi-diri.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi Diri', 'description' => 'Mengambil data ED (DataTables)'],
            ['name' => 'pemutu.evaluasi-diri.fill', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi Diri', 'description' => 'Mengisi evaluasi diri (capaian, analisis, bukti)'],
            ['name' => 'pemutu.evaluasi-diri.ptp-edit', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi Diri', 'description' => 'Mengisi pelaksanaan tindakan perbaikan (PTP)'],

            // Evaluasi KPI
            ['name' => 'pemutu.evaluasi-kpi.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi KPI', 'description' => 'Melihat capaian KPI'],
            ['name' => 'pemutu.evaluasi-kpi.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi KPI', 'description' => 'Mengambil data KPI (DataTables)'],
            ['name' => 'pemutu.evaluasi-kpi.fill', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – Evaluasi KPI', 'description' => 'Mengisi evaluasi KPI pegawai'],

            // ═══════════════════════════════════════════════════════
            // 6. AUDIT MUTU INTERNAL (AMI)
            // ═══════════════════════════════════════════════════════
            ['name' => 'pemutu.ami.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Melihat data AMI'],
            ['name' => 'pemutu.ami.view-all', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Melihat seluruh hasil AMI semua unit'],
            ['name' => 'pemutu.ami.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Mengambil data AMI (DataTables)'],
            ['name' => 'pemutu.ami.fill', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Mengisi temuan AMI (temuan, sebab, akibat, hasil)'],
            ['name' => 'pemutu.ami.rtp-edit', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Mengisi rencana tindakan perbaikan (RTP)'],
            ['name' => 'pemutu.ami.te-fill', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Mengisi tinjauan efektivitas (TE)'],
            ['name' => 'pemutu.ami.diskusi', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Mengirim diskusi/komentar pada AMI'],
            ['name' => 'pemutu.ami.export', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Evaluasi – AMI', 'description' => 'Export laporan AMI (PTK, temuan, positif)'],

            // ═══════════════════════════════════════════════════════
            // 7. PENGENDALIAN
            // ═══════════════════════════════════════════════════════
            ['name' => 'pemutu.pengendalian.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Melihat data pengendalian'],
            ['name' => 'pemutu.pengendalian.data', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Mengambil data pengendalian (DataTables)'],
            ['name' => 'pemutu.pengendalian.fill', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Mengisi pengendalian (status, analisis, matriks)'],
            ['name' => 'pemutu.pengendalian.validate', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Validasi pengendalian (atasan/supervisor)'],
            ['name' => 'pemutu.pengendalian.rtm-manage', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pengendalian', 'description' => 'Mengelola RTM Pengendalian (rapat tinjauan manajemen)'],

            // ═══════════════════════════════════════════════════════
            // 8. PENINGKATAN
            // ═══════════════════════════════════════════════════════
            ['name' => 'pemutu.peningkatan.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Peningkatan', 'description' => 'Melihat dashboard peningkatan'],
            ['name' => 'pemutu.peningkatan.duplicate', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Peningkatan', 'description' => 'Duplikasi standar/indikator ke periode baru'],
            ['name' => 'pemutu.peningkatan.review-edit', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Peningkatan', 'description' => 'Mengedit review item duplikasi'],
            ['name' => 'pemutu.peningkatan.delete-standar', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Peningkatan', 'description' => 'Menghapus standar target duplikasi'],
            ['name' => 'pemutu.peningkatan.approve-staging', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Peningkatan', 'description' => 'Approve staging (publish dokumen baru)'],
            ['name' => 'pemutu.peningkatan.rtm-manage', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Peningkatan', 'description' => 'Mengelola RTM Peningkatan'],

            // ═══════════════════════════════════════════════════════
            // 9. PELAKSANAAN / PEMANTAUAN
            // ═══════════════════════════════════════════════════════
            ['name' => 'pemutu.pemantauan.view', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pelaksanaan', 'description' => 'Melihat jadwal rapat pemantauan'],
            ['name' => 'pemutu.pemantauan.manage', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pelaksanaan', 'description' => 'Mengelola jadwal rapat pemantauan'],

            // ═══════════════════════════════════════════════════════
            // 10. PELAPORAN / EXPORT
            // ═══════════════════════════════════════════════════════
            ['name' => 'pemutu.export', 'category' => 'Penjaminan Mutu', 'sub_category' => 'Pelaporan', 'description' => 'Mengekspor laporan SPMI ke file'],
        ];

        // ──────────────────────────────────────────────────────────
        // CREATE / UPDATE ALL PERMISSIONS
        // ──────────────────────────────────────────────────────────
        foreach ($permissionData as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], [
                'guard_name' => 'web',
                'category' => $permission['category'],
                'sub_category' => $permission['sub_category'],
                'description' => $permission['description'],
            ]);
        }

        $allPermissions = array_column($permissionData, 'name');

        // Helper
        $givePermissions = function (string $roleName, array $permissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($permissions);
            }
        };

        // ──────────────────────────────────────────────────────────
        // ROLE ASSIGNMENTS
        // ──────────────────────────────────────────────────────────

        // ✅ ADMINISTRATOR — Full access to everything
        $givePermissions('Administrator', $allPermissions);

        // ✅ ADMIN SPMI — Full access to SPMI features (dedicated SPMI admin)
        $givePermissions('Admin SPMI', $allPermissions);

        // ✅ EKSEKUTIF — View-only high-level reports + export
        $givePermissions('Eksekutif', [
            'pemutu.dashboard.view',
            'pemutu.summary.view',
            'pemutu.summary.view',
            'pemutu.summary.view',
            'pemutu.ami.view',
            'pemutu.ami.view-all',
            'pemutu.export',
        ]);

        // ✅ PIMPINAN UNIT — View unit data + validate pengendalian + approve dokumen + export
        $givePermissions('Pimpinan Unit', [
            // Dashboard & Summary
            'pemutu.dashboard.view',
            'pemutu.summary.view',
            'pemutu.summary.view',

            // View settings
            'pemutu.label.view',
            'pemutu.periode-spmi.view',
            'pemutu.periode-kpi.view',
            'pemutu.tim-mutu.view',
            'pemutu.pegawai.view',

            // View dokumen & indikator
            'pemutu.dokumen.view',
            'pemutu.standar.view',
            'pemutu.indikator.view',

            // Approval — Pimpinan bisa approve dokumen yang dipilih
            'pemutu.approval.view',
            'pemutu.approval.process',

            // Evaluasi view
            'pemutu.evaluasi-diri.view',
            'pemutu.evaluasi-diri.data',
            'pemutu.evaluasi-kpi.view',
            'pemutu.evaluasi-kpi.data',

            // AMI view
            'pemutu.ami.view',
            'pemutu.ami.data',

            // Pengendalian — VALIDATE (key permission)
            'pemutu.pengendalian.view',
            'pemutu.pengendalian.data',
            'pemutu.pengendalian.validate',

            // Peningkatan view
            'pemutu.peningkatan.view',

            // Pelaksana view
            'pemutu.pemantauan.view',

            // Export
            'pemutu.export',
        ]);

        // ✅ AUDITOR INTERNAL — AMI fill + TE + diskusi + export + view all
        $givePermissions('Auditor Internal', [
            // Dashboard & Summary
            'pemutu.dashboard.view',
            'pemutu.summary.view',
            'pemutu.summary.view',
            'pemutu.summary.view',

            // View settings
            'pemutu.label.view',
            'pemutu.periode-spmi.view',
            'pemutu.periode-kpi.view',
            'pemutu.tim-mutu.view',
            'pemutu.pegawai.view',

            // View dokumen & indikator
            'pemutu.dokumen.view',
            'pemutu.standar.view',
            'pemutu.indikator.view',

            // Evaluasi view
            'pemutu.evaluasi-diri.view',
            'pemutu.evaluasi-diri.data',
            'pemutu.evaluasi-kpi.view',
            'pemutu.evaluasi-kpi.data',

            // AMI — FILL temuan + TE + diskusi
            'pemutu.ami.view',
            'pemutu.ami.data',
            'pemutu.ami.fill',
            'pemutu.ami.te-fill',
            'pemutu.ami.diskusi',

            // Pengendalian view
            'pemutu.pengendalian.view',
            'pemutu.pengendalian.data',

            // Peningkatan view
            'pemutu.peningkatan.view',

            // Pelaksana view
            'pemutu.pemantauan.view',

            // Export
            'pemutu.export',
        ]);

        // ✅ AUDITEE — ED fill + Pengendalian fill + KPI fill + RTP + PTP + add anggota + export
        $givePermissions('Auditee', [
            // Dashboard & Summary
            'pemutu.dashboard.view',
            'pemutu.summary.view',
            'pemutu.summary.view',
            'pemutu.summary.view',

            // View settings
            'pemutu.label.view',
            'pemutu.periode-spmi.view',
            'pemutu.periode-kpi.view',
            'pemutu.tim-mutu.view',
            'pemutu.pegawai.view',

            // View dokumen & indikator
            'pemutu.dokumen.view',
            'pemutu.standar.view',
            'pemutu.indikator.view',

            // Evaluasi Diri — FILL
            'pemutu.evaluasi-diri.view',
            'pemutu.evaluasi-diri.data',
            'pemutu.evaluasi-diri.fill',
            'pemutu.evaluasi-diri.ptp-edit',

            // Evaluasi KPI — FILL
            'pemutu.evaluasi-kpi.view',
            'pemutu.evaluasi-kpi.data',
            'pemutu.evaluasi-kpi.fill',

            // AMI — view + RTP fill (rencana perbaikan untuk KTS)
            'pemutu.ami.view',
            'pemutu.ami.data',
            'pemutu.ami.rtp-edit',
            'pemutu.ami.diskusi',

            // Pengendalian — FILL
            'pemutu.pengendalian.view',
            'pemutu.pengendalian.data',
            'pemutu.pengendalian.fill',

            // Peningkatan view
            'pemutu.peningkatan.view',

            // Pelaksana view
            'pemutu.pemantauan.view',

            // Tim Mutu — add anggota (auditee bisa tambah anggota, tapi bukan manage semua)
            'pemutu.tim-mutu.assign-auditee',

            // Export
            'pemutu.export',
        ]);

        // ✅ PEGAWAI — View all + export (read-only contributor)
        $givePermissions('Pegawai', [
            // Dashboard & Summary
            'pemutu.dashboard.view',
            'pemutu.summary.view',
            'pemutu.summary.view',
            'pemutu.summary.view',

            // View settings
            'pemutu.label.view',
            'pemutu.periode-spmi.view',
            'pemutu.periode-kpi.view',
            'pemutu.tim-mutu.view',
            'pemutu.pegawai.view',

            // View dokumen & indikator
            'pemutu.dokumen.view',
            'pemutu.standar.view',
            'pemutu.standar.data',
            'pemutu.indikator.view',
            'pemutu.indikator.data',

            // Evaluasi view
            'pemutu.evaluasi-diri.view',
            'pemutu.evaluasi-diri.data',
            'pemutu.evaluasi-kpi.view',
            'pemutu.evaluasi-kpi.data',

            // AMI view
            'pemutu.ami.view',
            'pemutu.ami.data',

            // Pengendalian view
            'pemutu.pengendalian.view',
            'pemutu.pengendalian.data',

            // Peningkatan view
            'pemutu.peningkatan.view',

            // Pelaksana view
            'pemutu.pemantauan.view',

            // Approval view
            'pemutu.approval.view',

            // Export
            'pemutu.export',
        ]);

        // ✅ GUEST — View all, NO export (observer)
        $givePermissions('Guest', [
            // Dashboard & Summary
            'pemutu.dashboard.view',
            'pemutu.summary.view',
            'pemutu.summary.view',
            'pemutu.summary.view',

            // View settings
            'pemutu.label.view',
            'pemutu.periode-spmi.view',
            'pemutu.periode-kpi.view',
            'pemutu.tim-mutu.view',
            'pemutu.pegawai.view',

            // View dokumen & indikator
            'pemutu.dokumen.view',
            'pemutu.standar.view',
            'pemutu.standar.data',
            'pemutu.indikator.view',
            'pemutu.indikator.data',

            // Evaluasi view
            'pemutu.evaluasi-diri.view',
            'pemutu.evaluasi-diri.data',
            'pemutu.evaluasi-kpi.view',
            'pemutu.evaluasi-kpi.data',

            // AMI view
            'pemutu.ami.view',
            'pemutu.ami.data',

            // Pengendalian view
            'pemutu.pengendalian.view',
            'pemutu.pengendalian.data',

            // Peningkatan view
            'pemutu.peningkatan.view',

            // Pelaksana view
            'pemutu.pemantauan.view',

            // Approval view
            'pemutu.approval.view',

            // ❌ NO EXPORT for Guest
        ]);

        Log::info('RolePermissionPemutuSeeder completed. Total permissions: '.count($allPermissions));
    }
}
