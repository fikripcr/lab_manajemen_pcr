<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\Jadwal;
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
        // Create 1000 semester records
        for ($i = 1; $i <= 20; $i++) {
            $tahun_awal = 2015 + ($i % 5);
            $tahun_akhir = $tahun_awal + 1;
            $tahun_ajaran = $tahun_awal . '/' . $tahun_akhir;
            $semester = ($i % 2) + 1; // Alternates between 1 and 2
            $is_active = $i === 1; // Only the first one is active

            Semester::create([
                'tahun_ajaran' => $tahun_ajaran,
                'semester' => $semester,
                'start_date' => fake()->date(),
                'end_date' => fake()->date(),
                'is_active' => $is_active
            ]);
        }

        // Create 1000 mata kuliah records
        for ($i = 1; $i <= 1000; $i++) {
            MataKuliah::create([
                'kode_mk' => 'MK' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_mk' => fake()->sentence(3),
                'sks' => fake()->numberBetween(2, 4)
            ]);
        }

        // Create 1000 lab records
        for ($i = 1; $i <= 500; $i++) {
            Lab::create([
                'name' => fake()->company() . ' Lab',
                'location' => fake()->address(),
                'capacity' => fake()->numberBetween(10, 50),
                'description' => fake()->paragraph(),
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

                Jadwal::create([
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
