<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\JadwalKuliah;
use App\Models\User;
use App\Models\Lab;
use Illuminate\Database\Seeder;

class AcademicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 semester records (25 tahun ajaran dengan genap dan ganjil)
        $tahunAjaran = [];
        for ($tahun = 2021; $tahun <= 2035; $tahun++) { // Dari 2021/2022 sampai 2035/2036
            $tahunAjaran[] = ['tahun_ajaran' => $tahun . '/' . ($tahun + 1), 'semester' => 1]; // Ganjil
            $tahunAjaran[] = ['tahun_ajaran' => $tahun . '/' . ($tahun + 1), 'semester' => 2]; // Genap
        }

        foreach ($tahunAjaran as $index => $data) {
            $is_active = $index === 0; // Hanya semester pertama yang aktif

            // Generate tanggal mulai dan selesai yang logis berdasarkan semester
            if ($data['semester'] == 1) { // Ganjil
                $start_date = $tahun . '-08-01'; // Agustus
                $end_date = $tahun . '-12-31';   // Desember
            } else { // Genap
                $tahunAkhir = explode('/', $data['tahun_ajaran'])[1];
                $start_date = $tahunAkhir . '-01-01'; // Januari
                $end_date = $tahunAkhir . '-06-30';   // Juni
            }

            Semester::create([
                'tahun_ajaran' => $data['tahun_ajaran'],
                'semester' => $data['semester'],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'is_active' => $is_active
            ]);
        }

        // Create 1000 mata kuliah records
        $subjects = [
            'Algoritma dan Struktur Data', 'Pemrograman Berorientasi Objek', 'Basis Data',
            'Jaringan Komputer', 'Sistem Operasi', 'Teknologi Web',
            'Kecerdasan Buatan', 'Rekayasa Perangkat Lunak', 'Grafika Komputer',
            'Analisis dan Desain Sistem', 'Interaksi Manusia dan Komputer', 'Keamanan Komputer',
            'Manajemen Proyek Perangkat Lunak', 'Big Data Analytics', 'Internet of Things',
            'Mobile Programming', 'Cloud Computing', 'Machine Learning',
            'Cybersecurity', 'Blockchain Technology'
        ];

        for ($i = 1; $i <= 1000; $i++) {
            if ($i <= count($subjects)) {
                $subjectName = $subjects[$i - 1]; // Use predefined subjects first
            } else {
                $subjectName = fake()->sentence(3, true); // Use faker for others
            }

            MataKuliah::create([
                'kode_mk' => 'MK' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_mk' => $subjectName,
                'sks' => fake()->numberBetween(2, 4)
            ]);
        }

        // Create 1000 lab records
        for ($i = 1; $i <= 500; $i++) {
            $faker = \Faker\Factory::create('id_ID'); // Use Indonesian locale

            Lab::create([
                'name' => $faker->company() . ' Laboratorium ' . $i,
                'location' => $faker->address(),
                'capacity' => $faker->numberBetween(10, 50),
                'description' => $faker->paragraph(),
            ]);
        }

        // Create 1000 schedule records
        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dosenUsers = User::role('dosen')->get();
        $semesterIds = Semester::pluck('semester_id')->toArray();
        $mataKuliahIds = MataKuliah::pluck('id')->toArray();
        $labIds = Lab::pluck('lab_id')->toArray(); // Changed to use 'id' instead of 'lab_id'

        for ($i = 1; $i <= 1000; $i++) {
            if ($dosenUsers->count() > 0 && !empty($semesterIds) && !empty($mataKuliahIds) && !empty($labIds)) {
                $jam_mulai = fake()->time('H:i', '07:00:00');
                $jam_selesai = fake()->time('H:i', '17:00:00');

                // Ensure jam_selesai is after jam_mulai
                if ($jam_mulai > $jam_selesai) {
                    $temp = $jam_mulai;
                    $jam_mulai = $jam_selesai;
                    $jam_selesai = $temp;
                }

                JadwalKuliah::create([
                    'semester_id' => $semesterIds[array_rand($semesterIds)],
                    'mata_kuliah_id' => $mataKuliahIds[array_rand($mataKuliahIds)],
                    'dosen_id' => $dosenUsers->random()->id,
                    'hari' => $hariOptions[array_rand($hariOptions)],
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai,
                    'lab_id' => $labIds[array_rand($labIds)], // This will now reference the 'id' field in labs
                ]);
            }
        }
    }
}
