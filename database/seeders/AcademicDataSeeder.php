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
        // Create sample semesters
        $semester1 = Semester::updateOrCreate(
            ['semester_id' => 1],
            [
                'tahun_ajaran' => '2023/2024',
                'semester' => 1, // Ganjil
                'start_date' => '2023-09-01',
                'end_date' => '2023-12-31',
                'is_active' => true
            ]
        );

        $semester2 = Semester::updateOrCreate(
            ['semester_id' => 2],
            [
                'tahun_ajaran' => '2023/2024',
                'semester' => 2, // Genap
                'start_date' => '2024-01-01',
                'end_date' => '2024-05-31',
                'is_active' => false
            ]
        );

        // Create sample mata kuliah
        $mk1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF101'],
            [
                'kode_mk' => 'IF101',
                'nama_mk' => 'Algoritma dan Pemrograman',
                'sks' => 3
            ]
        );

        $mk2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF202'],
            [
                'kode_mk' => 'IF202',
                'nama_mk' => 'Struktur Data',
                'sks' => 3
            ]
        );

        $mk3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'kode_mk' => 'IF303',
                'nama_mk' => 'Basis Data',
                'sks' => 3
            ]
        );

        // Create sample labs
        $lab1 = Lab::updateOrCreate(
            ['lab_id' => 'LAB001'],
            [
                'name' => 'Lab Jaringan',
                'location' => 'Gedung A, Lantai 1',
                'capacity' => 30,
                'description' => 'Laboratorium untuk praktikum jaringan komputer'
            ]
        );

        $lab2 = Lab::updateOrCreate(
            ['lab_id' => 'LAB002'],
            [
                'name' => 'Lab Pemrograman',
                'location' => 'Gedung A, Lantai 2',
                'capacity' => 35,
                'description' => 'Laboratorium untuk praktikum pemrograman'
            ]
        );

        // Get sample dosen user
        $dosen = User::whereHas('roles', function($query) {
            $query->where('name', 'dosen');
        })->first();

        if ($dosen) {
            // Create sample schedules
            Jadwal::updateOrCreate(
                [
                    'semester_id' => $semester1->semester_id,
                    'mata_kuliah_id' => $mk1->id,
                    'dosen_id' => $dosen->id,
                    'hari' => 'Senin',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '10:00',
                    'lab_id' => $lab1->lab_id,
                ]
            );

            Jadwal::updateOrCreate(
                [
                    'semester_id' => $semester1->semester_id,
                    'mata_kuliah_id' => $mk2->id,
                    'dosen_id' => $dosen->id,
                    'hari' => 'Selasa',
                    'jam_mulai' => '10:00',
                    'jam_selesai' => '12:00',
                    'lab_id' => $lab2->lab_id,
                ]
            );
        }
    }
}