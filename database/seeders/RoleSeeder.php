<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'mahasiswa',
            'dosen',
            'pic_lab',
            'teknisi',
            'ka_lab',
            'kajur',
            'penyelenggara_kegiatan', // Changed from 'penyelenggara' to maintain consistency
            'peserta_kegiatan'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
