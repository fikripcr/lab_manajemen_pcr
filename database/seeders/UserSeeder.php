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
            'mahasiswa', 'dosen', 'penanggung_jawab_lab', 'teknisi', 'kepala_lab', 'ketua_jurusan',
            'penyelenggara_kegiatan'
        ];

        // Create users with different roles
        for ($i = 1; $i <= 400; $i++) {
            $faker = \Faker\Factory::create('id_ID'); // Use Indonesian locale

            $firstName = $faker->firstName . ' ' . $faker->lastName;
            $email = 'user' . $i . '@contoh-lab.ac.id';
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
                'name' => $firstName,
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

        $this->command->info('Berhasil membuat 1000 contoh data pengguna dengan berbagai peran.');
    }
}
