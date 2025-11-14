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
            'penanggung_jawab_lab',
            'teknisi',
            'kepala_lab',
            'ketua_jurusan',
            'penyelenggara_kegiatan',
            'peserta_kegiatan'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
