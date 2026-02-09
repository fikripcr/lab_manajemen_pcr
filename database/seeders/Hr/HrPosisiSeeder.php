<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\Posisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrPosisiSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to refresh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Posisi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $posisi = [
            ['posisi' => 'Dosen Tetap', 'alias' => 'Dosen', 'is_active' => true],
            ['posisi' => 'Dosen Tidak Tetap', 'alias' => 'Dosen LB', 'is_active' => true],
            ['posisi' => 'Staff Administrasi', 'alias' => 'Admin', 'is_active' => true],
            ['posisi' => 'Laboran', 'alias' => 'PLP', 'is_active' => true],
            ['posisi' => 'Teknisi', 'alias' => 'Teknisi', 'is_active' => true],
            ['posisi' => 'Pustakawan', 'alias' => 'Pustakawan', 'is_active' => true],
        ];

        foreach ($posisi as $p) {
            Posisi::create($p);
        }
    }
}
