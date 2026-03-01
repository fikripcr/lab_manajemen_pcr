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
            ['name' => 'sys.dashboard.view', 'category' => 'System Management', 'sub_category' => 'Dashboard', 'description' => 'Melihat ringkasan statistik sistem pada dashboard'],
            ['name' => 'sys.error-log.view', 'category' => 'System Management', 'sub_category' => 'Error Log', 'description' => 'Melihat daftar catatan kesalahan (error) aplikasi'],
            ['name' => 'sys.error-log.create', 'category' => 'System Management', 'sub_category' => 'Error Log', 'description' => 'Menambah catatan error baru secara manual'],
            ['name' => 'sys.error-log.update', 'category' => 'System Management', 'sub_category' => 'Error Log', 'description' => 'Mengubah detail catatan error'],
            ['name' => 'sys.error-log.delete', 'category' => 'System Management', 'sub_category' => 'Error Log', 'description' => 'Menghapus catatan error dari sistem'],

            ['name' => 'sys.activity-log.view', 'category' => 'System Management', 'sub_category' => 'Activity Log', 'description' => 'Melihat log aktivitas seluruh pengguna'],
            ['name' => 'sys.activity-log.create', 'category' => 'System Management', 'sub_category' => 'Activity Log', 'description' => 'Menambah entri log aktivitas'],
            ['name' => 'sys.activity-log.update', 'category' => 'System Management', 'sub_category' => 'Activity Log', 'description' => 'Mengubah entri log aktivitas'],

            ['name' => 'sys.backup.view', 'category' => 'System Management', 'sub_category' => 'Backup', 'description' => 'Melihat daftar cadangan data sistem'],
            ['name' => 'sys.backup.create', 'category' => 'System Management', 'sub_category' => 'Backup', 'description' => 'Membuat cadangan data baru'],
            ['name' => 'sys.backup.update', 'category' => 'System Management', 'sub_category' => 'Backup', 'description' => 'Mengubah konfigurasi cadangan data'],
            ['name' => 'sys.backup.delete', 'category' => 'System Management', 'sub_category' => 'Backup', 'description' => 'Menghapus file cadangan data'],

            ['name' => 'sys.app-config.view', 'category' => 'System Management', 'sub_category' => 'Konfigurasi', 'description' => 'Melihat pengaturan global aplikasi'],
            ['name' => 'sys.app-config.update', 'category' => 'System Management', 'sub_category' => 'Konfigurasi', 'description' => 'Mengubah pengaturan global aplikasi'],

            ['name' => 'sys.server-monitor.view', 'category' => 'System Management', 'sub_category' => 'Monitor', 'description' => 'Melihat status kesehatan server'],

            ['name' => 'sys.notifications.view', 'category' => 'System Management', 'sub_category' => 'Notifikasi', 'description' => 'Melihat daftar notifikasi sistem'],
            ['name' => 'sys.notifications.create', 'category' => 'System Management', 'sub_category' => 'Notifikasi', 'description' => 'Mengirim notifikasi ke pengguna'],
            ['name' => 'sys.notifications.update', 'category' => 'System Management', 'sub_category' => 'Notifikasi', 'description' => 'Mengubah pesan notifikasi'],
            ['name' => 'sys.notifications.delete', 'category' => 'System Management', 'sub_category' => 'Notifikasi', 'description' => 'Menghapus notifikasi'],

            ['name' => 'sys.documentation.view', 'category' => 'System Management', 'sub_category' => 'Dokumentasi', 'description' => 'Melihat panduan penggunaan sistem'],

            ['name' => 'sys.permissions.view', 'category' => 'System Management', 'sub_category' => 'Hak Akses', 'description' => 'Melihat daftar permission sistem'],
            ['name' => 'sys.permissions.create', 'category' => 'System Management', 'sub_category' => 'Hak Akses', 'description' => 'Menambah permission baru'],
            ['name' => 'sys.permissions.update', 'category' => 'System Management', 'sub_category' => 'Hak Akses', 'description' => 'Mengubah detail permission'],
            ['name' => 'sys.permissions.delete', 'category' => 'System Management', 'sub_category' => 'Hak Akses', 'description' => 'Menghapus permission'],

            ['name' => 'sys.roles.view', 'category' => 'System Management', 'sub_category' => 'Role', 'description' => 'Melihat daftar peran (role) pengguna'],
            ['name' => 'sys.roles.create', 'category' => 'System Management', 'sub_category' => 'Role', 'description' => 'Menambah role baru'],
            ['name' => 'sys.roles.update', 'category' => 'System Management', 'sub_category' => 'Role', 'description' => 'Mengubah detail role'],
            ['name' => 'sys.roles.delete', 'category' => 'System Management', 'sub_category' => 'Role', 'description' => 'Menghapus role'],

            ['name' => 'sys.sys.test.email', 'category' => 'System Management', 'sub_category' => 'Testing', 'description' => 'Mengirim email uji coba'],
            ['name' => 'sys.sys.test.notification', 'category' => 'System Management', 'sub_category' => 'Testing', 'description' => 'Mengirim notifikasi uji coba'],
            ['name' => 'sys.sys.test.pdf-export', 'category' => 'System Management', 'sub_category' => 'Testing', 'description' => 'Menguji ekspor PDF'],

            // Impersonation
            ['name' => 'impersonate.login-as', 'category' => 'System Management', 'sub_category' => 'User Impersonation', 'description' => 'Login sebagai pengguna lain (Impersonasi)'],
            ['name' => 'impersonate.switch-back', 'category' => 'System Management', 'sub_category' => 'User Impersonation', 'description' => 'Kembali ke akun administrator asal'],
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
