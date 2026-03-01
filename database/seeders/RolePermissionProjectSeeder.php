<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionProjectSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionProjectSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionData = [
            ['name' => 'project.dashboard.view', 'category' => 'Manajemen Proyek', 'sub_category' => 'Dashboard', 'description' => 'Melihat statistik progres proyek'],
            ['name' => 'project.board.view', 'category' => 'Manajemen Proyek', 'sub_category' => 'Board', 'description' => 'Melihat papan tugas proyek'],
            ['name' => 'project.board.update', 'category' => 'Manajemen Proyek', 'sub_category' => 'Board', 'description' => 'Mengubah konfigurasi papan proyek'],

            ['name' => 'project.task.view', 'category' => 'Manajemen Proyek', 'sub_category' => 'Task', 'description' => 'Melihat detail tugas proyek'],
            ['name' => 'project.task.create', 'category' => 'Manajemen Proyek', 'sub_category' => 'Task', 'description' => 'Menambah tugas baru'],
            ['name' => 'project.task.update', 'category' => 'Manajemen Proyek', 'sub_category' => 'Task', 'description' => 'Mengubah status atau detail tugas'],
            ['name' => 'project.task.delete', 'category' => 'Manajemen Proyek', 'sub_category' => 'Task', 'description' => 'Menghapus tugas'],

            ['name' => 'project.repository.view', 'category' => 'Manajemen Proyek', 'sub_category' => 'Repository', 'description' => 'Melihat daftar aset proyek'],
            ['name' => 'project.repository.export', 'category' => 'Manajemen Proyek', 'sub_category' => 'Repository', 'description' => 'Mengekspor dokumentasi proyek'],
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
            $pimpinan->givePermissionTo(['project.dashboard.view', 'project.board.view']);
        }
    }
}
