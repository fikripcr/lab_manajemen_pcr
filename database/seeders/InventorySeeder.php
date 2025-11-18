<?php

namespace Database\Seeders;

use App\Models\Inventaris;
use App\Models\Lab;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Tidak Dapat Digunakan'];

        // Create 500 sample inventory records
        for ($i = 1; $i <= 500; $i++) {
            $faker = fake();

            // Generate equipment name using faker
            $equipmentName = $faker->company() . ' ' . $faker->words(rand(1,3), true) . ' ' . $i;

            // Generate equipment type using faker
            $equipmentTypeDetail = $faker->randomElement([
                'Elektronik', 'Furniture', 'Alat Laboratorium', 'Perangkat Jaringan',
                'Perangkat Lunak', 'Aksesori', 'Perlengkapan Kantor'
            ]) . ' ' . $faker->randomElement(['Dasar', 'Standar', 'Lengkap', 'Premium', 'Profesional']);

            Inventaris::create([
                'nama_alat' => $equipmentName,
                'jenis_alat' => $equipmentTypeDetail,
                'kondisi_terakhir' => $conditions[array_rand($conditions)],
                'tanggal_pengecekan' => $faker->dateTimeBetween('-6 months', '+1 month'),
            ]);
        }

        $this->command->info('Berhasil membuat 500 contoh data inventaris.');
    }
}
