<?php

namespace Database\Seeders;

use App\Models\Lab as LabModel;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1000 lab records
        for ($i = 1; $i <= 1000; $i++) {
            LabModel::create([
                'name' => fake()->company() . ' Lab ' . $i,
                'location' => fake()->address(),
                'capacity' => fake()->numberBetween(10, 60),
                'description' => fake()->paragraph(),
            ]);
        }

        $this->command->info('Created 1000 sample lab records.');
    }
}