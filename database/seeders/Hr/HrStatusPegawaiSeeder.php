<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\StatusPegawai;
use Illuminate\Database\Seeder;

class HrStatusPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kode_status' => '001', 'nama_status' => 'Tetap', 'organisasi' => 'YPCR'],
            ['kode_status' => '002', 'nama_status' => 'PKWT', 'organisasi' => 'YPCR'],
            ['kode_status' => '003', 'nama_status' => 'PKWT', 'organisasi' => 'Pihak Ketiga'],
            ['kode_status' => '004', 'nama_status' => 'PKWT', 'organisasi' => 'PCR'],
        ];

        foreach ($data as $item) {
            StatusPegawai::firstOrCreate(
                ['kode_status' => $item['kode_status']],
                [
                    'nama_status' => $item['nama_status'],
                    'organisasi'  => $item['organisasi'],
                    'is_active'   => true,
                ]
            );
        }
    }
}
