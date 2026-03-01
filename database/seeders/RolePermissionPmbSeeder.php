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
            ['name' => 'pmb.pendaftar.view', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Melihat daftar calon mahasiswa baru'],
            ['name' => 'pmb.pendaftar.create', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Mendaftarkan calon mahasiswa secara manual'],
            ['name' => 'pmb.pendaftar.update', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Mengubah status verifikasi pendaftar'],
            ['name' => 'pmb.pendaftar.delete', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Menghapus data pendaftar'],
            ['name' => 'pmb.pendaftar.export', 'category' => 'PMB', 'sub_category' => 'Pendaftar', 'description' => 'Mengekspor data pendaftar ke Excel'],

            ['name' => 'pmb.ujian.view', 'category' => 'PMB', 'sub_category' => 'Ujian Masuk', 'description' => 'Melihat jadwal dan absensi ujian'],
            ['name' => 'pmb.ujian.update', 'category' => 'PMB', 'sub_category' => 'Ujian Masuk', 'description' => 'Mengatur plotting peserta ujian'],

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
