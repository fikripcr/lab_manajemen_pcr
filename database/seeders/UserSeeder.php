<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define role names
        $roleNames = [
            'mahasiswa', 'dosen', 'pic_lab', 'teknisi', 'ka_lab', 'kajur',
            'penyelenggara_kegiatan'
        ];

        // Create users with different roles
        for ($i = 1; $i <= 1000; $i++) {
            $firstName = fake()->firstName;
            $lastName = fake()->lastName;
            $email = 'user' . $i . '@example.com';
            $role = $roleNames[array_rand($roleNames)];

            // Generate appropriate NIP/NPM based on role
            $nip = null;
            $npm = null;

            if (in_array($role, ['dosen', 'ka_lab', 'kajur', 'teknisi'])) {
                $nip = 'NIP' . sprintf('%08d', $i);
            } else {
                $npm = 'NPM' . sprintf('%08d', $i);
            }

            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'nip' => $nip,
                'npm' => $npm,
            ]);

            // Assign role to user
            $userRole = Role::firstOrCreate(['name' => $role]);
            $user->assignRole($userRole);
        }

        $this->command->info('Created 1000 sample user records with various roles.');
    }
}