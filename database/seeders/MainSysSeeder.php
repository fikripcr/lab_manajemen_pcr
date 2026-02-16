<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MainSysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('MainSysSeeder started');
        // Clear any cached permissions/roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permission categories and sub-categories
        $permissionData = [
            // System Management
            ['name' => 'sys.dashboard.view', 'category' => 'System Management', 'sub_category' => 'Dashboard'],
            ['name' => 'sys.error-log.view', 'category' => 'System Management', 'sub_category' => 'Error Log'],
            ['name' => 'sys.error-log.create', 'category' => 'System Management', 'sub_category' => 'Error Log'],
            ['name' => 'sys.error-log.update', 'category' => 'System Management', 'sub_category' => 'Error Log'],
            ['name' => 'sys.error-log.delete', 'category' => 'System Management', 'sub_category' => 'Error Log'],
            ['name' => 'sys.activity-log.view', 'category' => 'System Management', 'sub_category' => 'Activity Log'],
            ['name' => 'sys.activity-log.create', 'category' => 'System Management', 'sub_category' => 'Activity Log'],
            ['name' => 'sys.activity-log.update', 'category' => 'System Management', 'sub_category' => 'Activity Log'],
            ['name' => 'sys.backup.view', 'category' => 'System Management', 'sub_category' => 'Backup'],
            ['name' => 'sys.backup.create', 'category' => 'System Management', 'sub_category' => 'Backup'],
            ['name' => 'sys.backup.update', 'category' => 'System Management', 'sub_category' => 'Backup'],
            ['name' => 'sys.backup.delete', 'category' => 'System Management', 'sub_category' => 'Backup'],
            ['name' => 'sys.app-config.view', 'category' => 'System Management', 'sub_category' => 'App Configuration'],
            ['name' => 'sys.app-config.update', 'category' => 'System Management', 'sub_category' => 'App Configuration'],
            ['name' => 'sys.server-monitor.view', 'category' => 'System Management', 'sub_category' => 'Server Monitor'],
            ['name' => 'sys.notifications.view', 'category' => 'System Management', 'sub_category' => 'Notifications'],
            ['name' => 'sys.notifications.create', 'category' => 'System Management', 'sub_category' => 'Notifications'],
            ['name' => 'sys.notifications.update', 'category' => 'System Management', 'sub_category' => 'Notifications'],
            ['name' => 'sys.notifications.delete', 'category' => 'System Management', 'sub_category' => 'Notifications'],
            ['name' => 'sys.documentation.view', 'category' => 'System Management', 'sub_category' => 'Documentation'],
            ['name' => 'sys.permissions.view', 'category' => 'System Management', 'sub_category' => 'Permissions'],
            ['name' => 'sys.permissions.create', 'category' => 'System Management', 'sub_category' => 'Permissions'],
            ['name' => 'sys.permissions.update', 'category' => 'System Management', 'sub_category' => 'Permissions'],
            ['name' => 'sys.permissions.delete', 'category' => 'System Management', 'sub_category' => 'Permissions'],
            ['name' => 'sys.roles.view', 'category' => 'System Management', 'sub_category' => 'Roles'],
            ['name' => 'sys.roles.create', 'category' => 'System Management', 'sub_category' => 'Roles'],
            ['name' => 'sys.roles.update', 'category' => 'System Management', 'sub_category' => 'Roles'],
            ['name' => 'sys.roles.delete', 'category' => 'System Management', 'sub_category' => 'Roles'],
            ['name' => 'sys.sys.test.email', 'category' => 'System Management', 'sub_category' => 'Testing'],
            ['name' => 'sys.sys.test.notification', 'category' => 'System Management', 'sub_category' => 'Testing'],
            ['name' => 'sys.sys.test.pdf-export', 'category' => 'System Management', 'sub_category' => 'Testing'],

            // Impersonation
            ['name' => 'impersonate.login-as', 'category' => 'System Management', 'sub_category' => 'User Impersonation'],
            ['name' => 'impersonate.switch-back', 'category' => 'System Management', 'sub_category' => 'User Impersonation'],
        ];

        foreach ($permissionData as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
            ], [
                'guard_name'   => 'web',
                'category'     => $permission['category'],
                'sub_category' => $permission['sub_category'],
            ]);
        }

        // Create roles (excluding super_admin which will be handled by SysSuperAdminSeeder)
        $roles = [
            'admin', // Add the admin role to the basic roles
            'mahasiswa',
            'dosen',
            'penanggung_jawab_lab',
            'teknisi',
            'kepala_lab',
            'ketua_jurusan',
            'penyelenggara_kegiatan',
            'peserta_kegiatan',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create users with different roles (Merged from UserSeeder)
        $this->createUsers($roles);
    }

    private function createUsers(array $roleNames): void
    {
        // 1. Create specific admin user if not exists
        $adminEmail = 'admin@admin.com';
        if (! User::where('email', $adminEmail)->exists()) {
            $admin = User::create([
                'name'              => 'Super Admin',
                'email'             => $adminEmail,
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('admin');
        }

        // 2. Create dummy users
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 20; $i++) {
            $email = 'user' . $i . '@contoh-lab.ac.id';

            if (User::where('email', $email)->exists()) {
                continue;
            }

            try {
                $randomRole = $roleNames[array_rand($roleNames)];

                $user = User::create([
                    'name'              => $faker->firstName . ' ' . $faker->lastName,
                    'email'             => $email,
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);

                $user->assignRole($randomRole);
            } catch (\Exception $e) {
                // Log error or continue
            }
        }
    }
}
