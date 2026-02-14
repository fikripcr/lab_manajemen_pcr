<?php
namespace Database\Seeders\Lab;

use App\Models\Lab\JadwalKuliah;
use App\Models\Lab\Lab;
use App\Models\Lab\MataKuliah;
use App\Models\Lab\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;

class AcademicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 semester records
        $tahunSekarang = date('Y');
        for ($tahun = $tahunSekarang - 2; $tahun <= $tahunSekarang + 2; $tahun++) {
            // Ganjil
            Semester::create([
                'tahun_ajaran' => $tahun . '/' . ($tahun + 1),
                'semester'     => 'Ganjil',
                'start_date'   => $tahun . '-08-01',
                'end_date'     => $tahun . '-12-31',
                'is_active'    => ($tahun == $tahunSekarang),
            ]);
            // Genap
            Semester::create([
                'tahun_ajaran' => $tahun . '/' . ($tahun + 1),
                'semester'     => 'Genap',
                'start_date'   => ($tahun + 1) . '-01-01',
                'end_date'     => ($tahun + 1) . '-06-30',
                'is_active'    => false,
            ]);
        }

        // Create 50 mata kuliah records
        $subjects = [
            'Algoritma dan Struktur Data', 'Pemrograman Berorientasi Objek', 'Basis Data',
            'Jaringan Komputer', 'Sistem Operasi', 'Teknologi Web',
            'Kecerdasan Buatan', 'Rekayasa Perangkat Lunak', 'Grafika Komputer',
            'Analisis dan Desain Sistem', 'Interaksi Manusia dan Komputer', 'Keamanan Komputer',
            'Manajemen Proyek Perangkat Lunak', 'Big Data Analytics', 'Internet of Things',
            'Mobile Programming', 'Cloud Computing', 'Machine Learning',
            'Cybersecurity', 'Blockchain Technology',
        ];

        for ($i = 1; $i <= 50; $i++) {
            $subjectName = ($i <= count($subjects)) ? $subjects[$i - 1] : fake()->sentence(3, true);
            MataKuliah::create([
                'kode_mk' => 'MK' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_mk' => $subjectName,
                'sks'     => fake()->numberBetween(2, 4),
            ]);
        }

        // Create 20 lab records
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 1; $i <= 20; $i++) {
            Lab::create([
                'name'        => 'Laboratorium ' . $faker->colorName . ' ' . $i,
                'location'    => $faker->address(),
                'capacity'    => $faker->numberBetween(10, 50),
                'description' => $faker->paragraph(),
            ]);
        }

        // Create 50 schedule records
        $hariOptions   = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dosenUsers    = User::role('dosen')->get();
        $semesterIds   = Semester::pluck('semester_id')->toArray();
        $mataKuliahIds = MataKuliah::pluck('mata_kuliah_id')->toArray();
        $labIds        = Lab::pluck('lab_id')->toArray();

        if ($dosenUsers->isNotEmpty() && ! empty($semesterIds) && ! empty($mataKuliahIds) && ! empty($labIds)) {
            for ($i = 1; $i <= 50; $i++) {
                $jam_mulai   = fake()->time('H:i', '07:00:00');
                $jam_selesai = date('H:i', strtotime($jam_mulai . ' + ' . rand(1, 4) . ' hours'));

                JadwalKuliah::create([
                    'semester_id'    => $semesterIds[array_rand($semesterIds)],
                    'mata_kuliah_id' => $mataKuliahIds[array_rand($mataKuliahIds)],
                    'dosen_id'       => $dosenUsers->random()->id,
                    'hari'           => $hariOptions[array_rand($hariOptions)],
                    'jam_mulai'      => $jam_mulai,
                    'jam_selesai'    => $jam_selesai,
                    'lab_id'         => $labIds[array_rand($labIds)],
                ]);
            }
        }
    }
}
