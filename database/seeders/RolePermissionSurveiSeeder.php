<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionSurveiSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionSurveiSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionData = [
            // ── DASHBOARD ─────────────────────────────────────────────────────
            ['name' => 'survei.dashboard.view', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard statistik survei'],

            // ── MANAJEMEN SURVEI ──────────────────────────────────────────────
            // Maps to: Buat Survei Baru → Survey Builder
            ['name' => 'survei.kuesioner.view', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Manajemen Survei', 'description' => 'Melihat daftar survei aktif'],
            ['name' => 'survei.kuesioner.data', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Manajemen Survei', 'description' => 'Mengambil data survei (DataTables)'],
            ['name' => 'survei.kuesioner.create', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Manajemen Survei', 'description' => 'Membuat survei baru (Buat Survei Baru)'],
            ['name' => 'survei.kuesioner.update', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Manajemen Survei', 'description' => 'Mengubah pertanyaan / pengaturan survei (Builder)'],
            ['name' => 'survei.kuesioner.delete', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Manajemen Survei', 'description' => 'Menghapus survei'],

            // ── RESPONDEN ─────────────────────────────────────────────────────
            ['name' => 'survei.responden.view', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Responden', 'description' => 'Melihat daftar responden survei'],
            ['name' => 'survei.responden.data', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Responden', 'description' => 'Mengambil data responden (DataTables)'],

            // ── LAPORAN & ANALISIS ────────────────────────────────────────────
            ['name' => 'survei.laporan.view', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Laporan & Analisis', 'description' => 'Melihat tabulasi dan grafik hasil survei'],
            ['name' => 'survei.laporan.data', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Laporan & Analisis', 'description' => 'Mengambil data laporan (DataTables)'],
            ['name' => 'survei.laporan.export', 'category' => 'Umpan Balik (Survei)', 'sub_category' => 'Laporan & Analisis', 'description' => 'Mengekspor laporan hasil survei'],
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
        $eksekutif = Role::where('name', 'Eksekutif')->first();
        if ($eksekutif) {
            $eksekutif->givePermissionTo(['survei.dashboard.view', 'survei.laporan.view', 'survei.laporan.export']);
        }
    }
}
