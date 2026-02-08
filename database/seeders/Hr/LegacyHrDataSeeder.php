<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\Departemen;
use App\Models\Hr\JabatanStruktural;
use App\Models\Hr\Posisi;
use App\Models\Hr\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyHrDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Departemen
            $deptTI = Departemen::create([
                'departemen' => 'Teknologi Informasi',
                'abbr'       => 'JTI',
                'alias'      => 'JTI',
                'is_active'  => true,
            ]);

            $deptBisnis = Departemen::create([
                'departemen' => 'Bisnis',
                'abbr'       => 'JAB',
                'alias'      => 'JAB',
                'is_active'  => true,
            ]);

            // 2. Prodi
            Prodi::create(['nama_prodi' => 'D3 Teknik Komputer', 'alias' => 'TK', 'jenjang_pendidikan' => 'D3', 'departemen_id' => $deptTI->departemen_id]);
            Prodi::create(['nama_prodi' => 'D4 Teknik Informatika', 'alias' => 'TI', 'jenjang_pendidikan' => 'D4', 'departemen_id' => $deptTI->departemen_id]);
            Prodi::create(['nama_prodi' => 'D3 Akuntansi', 'alias' => 'AK', 'jenjang_pendidikan' => 'D3', 'departemen_id' => $deptBisnis->departemen_id]);

            // 3. Jabatan Struktural
            JabatanStruktural::create(['jabstruktural' => 'Direktur', 'is_active' => true]);
            JabatanStruktural::create(['jabstruktural' => 'Wakil Direktur 1', 'is_active' => true]);
            JabatanStruktural::create(['jabstruktural' => 'Wakil Direktur 2', 'is_active' => true]);

            // 4. Posisi
            Posisi::create(['posisi' => 'Dosen Tetap', 'alias' => 'Dosen', 'is_active' => true]);
            Posisi::create(['posisi' => 'Staff Administrasi', 'alias' => 'Admin', 'is_active' => true]);
        });
    }
}
