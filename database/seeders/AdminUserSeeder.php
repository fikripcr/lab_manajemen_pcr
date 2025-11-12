<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update the admin user
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Use a strong password in production
                'email_verified_at' => now(),
            ]
        );

        // Get or create the admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Assign the admin role to the user
        $admin->assignRole('admin');
        
        // Also create other necessary roles based on user requirements
        $roles = [
            'mahasiswa',
            'dosen', 
            'pic_lab',
            'teknisi',
            'ka_lab',
            'kajur',
            'penyelenggara_kegiatan',
            'peserta_kegiatan'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
        
        // Assign the kajur role to the admin user as well so they have full access
        $admin->assignRole('kajur');
    }
}