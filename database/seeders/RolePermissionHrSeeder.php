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
            ['name' => 'hr.dashboard.view', 'category' => 'SDM (HR)', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard statistik kepegawaian'],
            ['name' => 'hr.pegawai.view', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Melihat daftar profil pegawai di unit sendiri'],
            ['name' => 'hr.pegawai.view-all', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Melihat data seluruh pegawai perusahaan'],
            ['name' => 'hr.pegawai.view-own', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Melihat profil diri sendiri'],
            ['name' => 'hr.pegawai.create', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Menambah data pegawai baru'],
            ['name' => 'hr.pegawai.update', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Mengubah detail data pegawai'],
            ['name' => 'hr.pegawai.delete', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Menghapus data pegawai'],
            ['name' => 'hr.pegawai.export', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Mengekspor daftar pegawai ke Excel/PDF'],
            ['name' => 'hr.pegawai.import', 'category' => 'SDM (HR)', 'sub_category' => 'Pegawai', 'description' => 'Mengimpor data pegawai dari file'],

            ['name' => 'hr.presensi.view', 'category' => 'SDM (HR)', 'sub_category' => 'Presensi', 'description' => 'Melihat rekap kehadiran pegawai'],
            ['name' => 'hr.presensi.view-own', 'category' => 'SDM (HR)', 'sub_category' => 'Presensi', 'description' => 'Melihat riwayat kehadiran pribadi'],
            ['name' => 'hr.presensi.update', 'category' => 'SDM (HR)', 'sub_category' => 'Presensi', 'description' => 'Melakukan koreksi data presensi'],

            ['name' => 'hr.cuti.view', 'category' => 'SDM (HR)', 'sub_category' => 'Cuti', 'description' => 'Melihat daftar pengajuan cuti'],
            ['name' => 'hr.cuti.update', 'category' => 'SDM (HR)', 'sub_category' => 'Cuti', 'description' => 'Menyetujui atau menolak permohonan cuti'],

            ['name' => 'hr.gaji.view', 'category' => 'SDM (HR)', 'sub_category' => 'Penggajian', 'description' => 'Melihat rekapitulasi gaji pegawai'],
            ['name' => 'hr.gaji.view-own', 'category' => 'SDM (HR)', 'sub_category' => 'Penggajian', 'description' => 'Melihat slip gaji pribadi'],
            ['name' => 'hr.gaji.update', 'category' => 'SDM (HR)', 'sub_category' => 'Penggajian', 'description' => 'Memproses data penggajian'],
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
            $pimpinan->givePermissionTo(['hr.pegawai.view', 'hr.presensi.view', 'hr.cuti.view']);
        }

        $eksekutif = Role::where('name', 'Eksekutif')->first();
        if ($eksekutif) {
            $eksekutif->givePermissionTo(['hr.dashboard.view', 'hr.pegawai.view-all', 'hr.gaji.view']);
        }
    }
}
