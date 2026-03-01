<?php
namespace Database\Seeders;

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

        // Create Core Roles (Generalized & Global)
        $roles = [
            'Super Administrator',
            'Administrator',
            'Pimpinan Unit',
            'Eksekutif',
            'Pegawai',
            'Dosen',
            'Mahasiswa',
        ];

        // Cleanup: Remove old/overlapping roles (like lowercase 'admin')
        // that are not in the new standardized list.
        $standardRoles = array_merge($roles, [
            'Calon Mahasiswa',
            'Auditor Internal',
            'Auditor Eksternal',
            'Teknisi',
        ]);
        Role::whereNotIn('name', $standardRoles)->delete();

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
                'name'              => 'Administrator',
                'email'             => $adminEmail,
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('Administrator');
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
