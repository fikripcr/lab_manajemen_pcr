<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\JenisIzin;
use Illuminate\Database\Seeder;

class HrJenisIzinSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'            => 'Istri Pegawai melahirkan/keguguran kandungan',
                'kategori'        => 'Izin',
                'max_hari'        => 3,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Istri/Suam/Anak/Menantu Pegawai meninggal dunia',
                'kategori'        => 'Izin',
                'max_hari'        => 3,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Orang Tua/Mertua Pegawai meninggal dunia',
                'kategori'        => 'Izin',
                'max_hari'        => 3,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Saudara kandung/ipar Pegawai meninggal dunia',
                'kategori'        => 'Izin',
                'max_hari'        => 3,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Perkawinan Pegawai',
                'kategori'        => 'Izin',
                'max_hari'        => 3,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Perkawinan saudara kandung/ipar Pegawai',
                'kategori'        => 'Izin',
                'max_hari'        => 2,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Perkawinan anak Pegawai',
                'kategori'        => 'Izin',
                'max_hari'        => 2,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Mengkhitankan anak Pegawai',
                'kategori'        => 'Izin',
                'max_hari'        => 2,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Mengaqaqahkan atau Membaptiskan anak Pegawai',
                'kategori'        => 'Izin',
                'max_hari'        => 2,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Anggota keluarga dalam satu rumah meninggal',
                'kategori'        => 'Izin',
                'max_hari'        => 2,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Pegawai Sakit',
                'kategori'        => 'Izin',
                'max_hari'        => 0,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Pegawai menunaikan ibadah Haji',
                'kategori'        => 'Izin',
                'max_hari'        => 60,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Meninggalkan pekerjaan tanpa gaji',
                'kategori'        => 'Izin',
                'max_hari'        => 0,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Lain-Lain / Izin Meninggalkan Pekerjaan',
                'kategori'        => 'Izin',
                'max_hari'        => 0,
                'pemilihan_waktu' => 'date_single',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Izin Menyusui',
                'kategori'        => 'Izin',
                'max_hari'        => 0,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Cuti Tahunan',
                'kategori'        => 'Cuti',
                'max_hari'        => null,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1', 'Atasan 2']),
                'is_active'       => 1,
            ],
            [
                'nama'            => 'Cuti Melahirkan',
                'kategori'        => 'Cuti',
                'max_hari'        => 60,
                'pemilihan_waktu' => 'date_multiple',
                'urutan_approval' => json_encode(['Atasan 1', 'Kepegawaian']),
                'is_active'       => 1,
            ],
        ];

        foreach ($data as $item) {
            JenisIzin::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
