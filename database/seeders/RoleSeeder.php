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
        Role::create(['name' => 'mahasiswa']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'pic_lab']);
        Role::create(['name' => 'teknisi']);
        Role::create(['name' => 'ka_lab']);
        Role::create(['name' => 'kajur']);
        Role::create(['name' => 'penyelenggara']);
        // 'Peserta Kegiatan' tidak perlu role karena mereka tidak login (via QR)
    }
}
