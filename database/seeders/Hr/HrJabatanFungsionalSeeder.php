<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\JabatanFungsional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrJabatanFungsionalSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to refresh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JabatanFungsional::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $jabatan = [
            ['kode_jabatan' => 'AA', 'jabfungsional' => 'Asisten Ahli', 'tunjangan' => 375000, 'is_active' => true],
            ['kode_jabatan' => 'L', 'jabfungsional' => 'Lektor', 'tunjangan' => 700000, 'is_active' => true],
            ['kode_jabatan' => 'LK', 'jabfungsional' => 'Lektor Kepala', 'tunjangan' => 900000, 'is_active' => true],
            ['kode_jabatan' => 'GB', 'jabfungsional' => 'Guru Besar', 'tunjangan' => 1350000, 'is_active' => true],
            ['kode_jabatan' => 'TP', 'jabfungsional' => 'Tenaga Pengajar', 'tunjangan' => 0, 'is_active' => true],
        ];

        foreach ($jabatan as $j) {
            JabatanFungsional::create($j);
        }
    }
}
