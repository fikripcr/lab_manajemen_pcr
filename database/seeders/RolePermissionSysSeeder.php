<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionSysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('RolePermissionSysSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // System Permissions (Generalized)
        $permissionData = [
            // Dashboard
            ['name' => 'sys.dashboard.view', 'category' => 'System Management', 'sub_category' => 'Dashboard', 'description' => 'Melihat ringkasan statistik sistem pada dashboard'],

            // Users
            ['name' => 'sys.users.view', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Melihat daftar pengguna'],
            ['name' => 'sys.users.data', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Mengambil data pengguna (DataTables)'],
            ['name' => 'sys.users.create', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Menambah pengguna baru'],
            ['name' => 'sys.users.update', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Mengubah data pengguna'],
            ['name' => 'sys.users.delete', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Menghapus pengguna'],
            ['name' => 'sys.users.export', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Mengekspor data pengguna'],
            ['name' => 'sys.users.import', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Mengimpor data pengguna'],

            // Roles & Permissions
            ['name' => 'sys.roles.view', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Melihat daftar peran (role)'],
            ['name' => 'sys.roles.create', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Menambah peran baru'],
            ['name' => 'sys.roles.update', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Mengubah detail peran'],
            ['name' => 'sys.roles.delete', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Menghapus peran'],
            ['name' => 'sys.permissions.view', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Melihat daftar perizinan (permission)'],
            ['name' => 'sys.permissions.data', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Mengambil data perizinan (DataTables)'],
            ['name' => 'sys.permissions.create', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Menambah perizinan baru'],
            ['name' => 'sys.permissions.update', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Mengubah detail perizinan'],
            ['name' => 'sys.permissions.delete', 'category' => 'System Management', 'sub_category' => 'Role & Hak Akses', 'description' => 'Menghapus perizinan'],

            // Logs
            ['name' => 'sys.error-log.view', 'category' => 'System Management', 'sub_category' => 'Logs', 'description' => 'Melihat daftar catatan kesalahan'],
            ['name' => 'sys.error-log.data', 'category' => 'System Management', 'sub_category' => 'Logs', 'description' => 'Mengambil data error log (DataTables)'],
            ['name' => 'sys.error-log.delete', 'category' => 'System Management', 'sub_category' => 'Logs', 'description' => 'Menghapus catatan kesalahan'],
            ['name' => 'sys.activity-log.view', 'category' => 'System Management', 'sub_category' => 'Logs', 'description' => 'Melihat log aktivitas'],
            ['name' => 'sys.activity-log.data', 'category' => 'System Management', 'sub_category' => 'Logs', 'description' => 'Mengambil data activity log (DataTables)'],

            // Config & Utilities
            ['name' => 'sys.app-config.view', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Melihat pengaturan global'],
            ['name' => 'sys.app-config.update', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Mengubah pengaturan global'],
            ['name' => 'sys.backup.view', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Melihat daftar cadangan data'],
            ['name' => 'sys.backup.create', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Membuat cadangan data'],
            ['name' => 'sys.backup.delete', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Menghapus file cadangan'],
            ['name' => 'sys.notifications.view', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Melihat daftar notifikasi'],
            ['name' => 'sys.notifications.data', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Mengambil data notifikasi (DataTables)'],
            ['name' => 'sys.notifications.create', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Mengirim notifikasi'],
            ['name' => 'sys.documentation.view', 'category' => 'System Management', 'sub_category' => 'Utilities', 'description' => 'Melihat panduan penggunaan'],

            // Testing
            ['name' => 'sys.test.email', 'category' => 'System Management', 'sub_category' => 'Testing', 'description' => 'Mengirim email uji coba'],
            ['name' => 'sys.test.notification', 'category' => 'System Management', 'sub_category' => 'Testing', 'description' => 'Mengirim notifikasi uji coba'],
            ['name' => 'sys.test.pdf-export', 'category' => 'System Management', 'sub_category' => 'Testing', 'description' => 'Menguji ekspor PDF'],

            // Impersonation
            ['name' => 'sys.users.impersonate', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Login sebagai pengguna lain'],
            ['name' => 'sys.users.reset-password', 'category' => 'System Management', 'sub_category' => 'User', 'description' => 'Mereset password pengguna'],
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

        // Assign to Administrator Role
        $admin = Role::where('name', 'Administrator')->first();
        if ($admin) {
            $admin->syncPermissions(array_column($permissionData, 'name'));
        }
    }
}
