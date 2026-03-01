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
            // ── DASHBOARD ─────────────────────────────────────────────────────
            ['name' => 'project.dashboard.view', 'category' => 'Project Management', 'sub_category' => 'Dashboard', 'description' => 'Melihat statistik progres proyek'],
            ['name' => 'project.dashboard.data', 'category' => 'Project Management', 'sub_category' => 'Dashboard', 'description' => 'Mengambil data dashboard proyek (DataTables)'],

            // ── DAFTAR PROYEK ─────────────────────────────────────────────────
            ['name' => 'project.view', 'category' => 'Project Management', 'sub_category' => 'Daftar Proyek', 'description' => 'Melihat daftar semua proyek'],
            ['name' => 'project.data', 'category' => 'Project Management', 'sub_category' => 'Daftar Proyek', 'description' => 'Mengambil data proyek (DataTables)'],
            ['name' => 'project.create', 'category' => 'Project Management', 'sub_category' => 'Daftar Proyek', 'description' => 'Membuat proyek baru'],
            ['name' => 'project.update', 'category' => 'Project Management', 'sub_category' => 'Daftar Proyek', 'description' => 'Mengubah informasi proyek'],
            ['name' => 'project.delete', 'category' => 'Project Management', 'sub_category' => 'Daftar Proyek', 'description' => 'Menghapus proyek'],

            // ── BOARD & TASK (Detail Proyek) ──────────────────────────────────
            ['name' => 'project.board.view', 'category' => 'Project Management', 'sub_category' => 'Board', 'description' => 'Melihat papan tugas proyek'],
            ['name' => 'project.board.data', 'category' => 'Project Management', 'sub_category' => 'Board', 'description' => 'Mengambil data board (DataTables)'],
            ['name' => 'project.board.update', 'category' => 'Project Management', 'sub_category' => 'Board', 'description' => 'Mengubah konfigurasi kolom papan proyek'],
            ['name' => 'project.task.view', 'category' => 'Project Management', 'sub_category' => 'Task', 'description' => 'Melihat detail tugas proyek'],
            ['name' => 'project.task.data', 'category' => 'Project Management', 'sub_category' => 'Task', 'description' => 'Mengambil data tugas (DataTables)'],
            ['name' => 'project.task.create', 'category' => 'Project Management', 'sub_category' => 'Task', 'description' => 'Menambah tugas baru'],
            ['name' => 'project.task.update', 'category' => 'Project Management', 'sub_category' => 'Task', 'description' => 'Mengubah status atau detail tugas'],
            ['name' => 'project.task.delete', 'category' => 'Project Management', 'sub_category' => 'Task', 'description' => 'Menghapus tugas'],

            // ── REPOSITORY ────────────────────────────────────────────────────
            ['name' => 'project.repository.view', 'category' => 'Project Management', 'sub_category' => 'Repository', 'description' => 'Melihat daftar aset dan dokumen proyek'],
            ['name' => 'project.repository.data', 'category' => 'Project Management', 'sub_category' => 'Repository', 'description' => 'Mengambil data repository (DataTables)'],
            ['name' => 'project.repository.export', 'category' => 'Project Management', 'sub_category' => 'Repository', 'description' => 'Mengekspor dokumentasi proyek'],
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
        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['project.dashboard.view', 'project.view', 'project.board.view']);
        }
    }
}
