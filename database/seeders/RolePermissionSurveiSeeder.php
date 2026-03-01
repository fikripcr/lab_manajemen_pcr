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
            ['name' => 'survei.dashboard.view', 'category' => 'Survei', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard statistik survei'],
            ['name' => 'survei.kuesioner.view', 'category' => 'Survei', 'sub_category' => 'Kuesioner', 'description' => 'Melihat daftar kuesioner aktif'],
            ['name' => 'survei.kuesioner.create', 'category' => 'Survei', 'sub_category' => 'Kuesioner', 'description' => 'Membuat instrumen kuesioner baru'],
            ['name' => 'survei.kuesioner.update', 'category' => 'Survei', 'sub_category' => 'Kuesioner', 'description' => 'Mengubah butir pertanyaan'],

            ['name' => 'survei.responden.view', 'category' => 'Survei', 'sub_category' => 'Responden', 'description' => 'Melihat daftar responden survei'],

            ['name' => 'survei.laporan.view', 'category' => 'Survei', 'sub_category' => 'Laporan', 'description' => 'Melihat tabulasi hasil survei'],
            ['name' => 'survei.laporan.export', 'category' => 'Survei', 'sub_category' => 'Laporan', 'description' => 'Mengekspor laporan hasil survei'],
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
            $eksekutif->givePermissionTo(['survei.dashboard.view', 'survei.laporan.view']);
        }
    }
}
