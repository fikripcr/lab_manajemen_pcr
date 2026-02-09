<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\JenisIndisipliner;
use Illuminate\Database\Seeder;

class HrJenisIndisiplinerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Nasihat & Petunjuk',
            'Teguran Lisan Tertulis',
            'Peringatan Tertulis Pertama',
            'Peringatan Tertulis Kedua',
            'Peringatan Tertulis Ketiga',
            'Penurunan Kelas Gaji',
            'PHK',
        ];

        foreach ($data as $item) {
            JenisIndisipliner::updateOrCreate(['jenis_indisipliner' => $item]);
        }
    }
}
