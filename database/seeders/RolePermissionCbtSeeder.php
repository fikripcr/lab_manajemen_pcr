<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionCbtSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionCbtSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionData = [
            ['name' => 'cbt.dashboard.view', 'category' => 'CBT', 'sub_category' => 'Dashboard', 'description' => 'Melihat statistik hasil ujian online'],
            ['name' => 'cbt.ujian.view', 'category' => 'CBT', 'sub_category' => 'Ujian', 'description' => 'Melihat daftar sesi ujian'],
            ['name' => 'cbt.ujian.create', 'category' => 'CBT', 'sub_category' => 'Ujian', 'description' => 'Membuat sesi ujian baru'],
            ['name' => 'cbt.ujian.update', 'category' => 'CBT', 'sub_category' => 'Ujian', 'description' => 'Mengatur parameter sesi ujian'],
            ['name' => 'cbt.ujian.delete', 'category' => 'CBT', 'sub_category' => 'Ujian', 'description' => 'Menghapus sesi ujian'],

            ['name' => 'cbt.soal.view', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Melihat daftar koleksi soal'],
            ['name' => 'cbt.soal.create', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Menambah butir soal baru'],
            ['name' => 'cbt.soal.update', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Mengubah butir soal'],
            ['name' => 'cbt.soal.import', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Mengimpor soal dari file'],

            ['name' => 'cbt.hasil.view', 'category' => 'CBT', 'sub_category' => 'Hasil Ujian', 'description' => 'Melihat nilai ujian peserta'],
            ['name' => 'cbt.hasil.export', 'category' => 'CBT', 'sub_category' => 'Hasil Ujian', 'description' => 'Mengekspor rekap nilai'],
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
            $pimpinan->givePermissionTo(['cbt.dashboard.view', 'cbt.hasil.view']);
        }
    }
}
