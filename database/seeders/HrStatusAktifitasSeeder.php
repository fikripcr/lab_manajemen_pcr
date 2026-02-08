<?php
namespace Database\Seeders;

use App\Models\Hr\StatusAktifitas;
use Illuminate\Database\Seeder;

class HrStatusAktifitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode_status' => '001', 'nama_status' => 'Aktif'],
            ['kode_status' => '003', 'nama_status' => 'Resign'],
            ['kode_status' => '004', 'nama_status' => 'Habis Kontrak'],
            ['kode_status' => '005', 'nama_status' => 'Pensiun'],
            ['kode_status' => '006', 'nama_status' => 'Meninggal Dunia'],
            ['kode_status' => '007', 'nama_status' => 'LWP'],
            ['kode_status' => '008', 'nama_status' => 'Tugas Belajar'],
            ['kode_status' => '009', 'nama_status' => 'Pensiun Dini'],
        ];

        foreach ($data as $item) {
            StatusAktifitas::firstOrCreate(
                ['kode_status' => $item['kode_status']],
                [
                    'nama_status' => $item['nama_status'],
                    'is_active'   => true,
                ]
            );
        }
    }
}
