<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            LabSeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,
            InventorySeeder::class,
            PengumumanSeeder::class,
            AcademicDataSeeder::class,
        ]);

        // Create some test users if needed
        // User::factory(10)->create();
    }
}
