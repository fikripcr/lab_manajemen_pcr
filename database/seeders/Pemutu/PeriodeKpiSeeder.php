<?php
namespace Database\Seeders\Pemutu;

use App\Models\Pemutu\PeriodeKpi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PeriodeKpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Periode Ganjil 2024/2025 (Aktif)
        PeriodeKpi::updateOrCreate(
            ['tahun_akademik' => '2024/2025', 'semester' => 'Ganjil'],
            [
                'nama'            => 'Semester Ganjil 2024/2025',
                'tahun'           => 2024,
                'tanggal_mulai'   => Carbon::parse('2024-08-01'),
                'tanggal_selesai' => Carbon::parse('2025-01-31'),
                'is_active'       => true,
            ]
        );

        // Periode Genap 2023/2024
        PeriodeKpi::updateOrCreate(
            ['tahun_akademik' => '2023/2024', 'semester' => 'Genap'],
            [
                'nama'            => 'Semester Genap 2023/2024',
                'tahun'           => 2024,
                'tanggal_mulai'   => Carbon::parse('2024-02-01'),
                'tanggal_selesai' => Carbon::parse('2024-07-31'),
                'is_active'       => false,
            ]
        );

        // Periode Ganjil 2023/2024
        PeriodeKpi::updateOrCreate(
            ['tahun_akademik' => '2023/2024', 'semester' => 'Ganjil'],
            [
                'nama'            => 'Semester Ganjil 2023/2024',
                'tahun'           => 2023,
                'tanggal_mulai'   => Carbon::parse('2023-08-01'),
                'tanggal_selesai' => Carbon::parse('2024-01-31'),
                'is_active'       => false,
            ]
        );
    }
}
