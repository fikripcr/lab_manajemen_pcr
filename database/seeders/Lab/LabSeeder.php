<?php
namespace Database\Seeders\Lab;

use App\Models\Lab as LabModel;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1000 lab records using Indonesian locale
        for ($i = 1; $i <= 1000; $i++) {
            $faker = \Faker\Factory::create('id_ID'); // Use Indonesian locale

            LabModel::create([
                'name'        => $faker->company() . ' Laboratorium ' . $i,
                'location'    => $faker->address(),
                'capacity'    => $faker->numberBetween(10, 60),
                'description' => $faker->paragraph(),
            ]);
        }

        $this->command->info('Berhasil membuat 1000 contoh data laboratorium.');
    }
}
