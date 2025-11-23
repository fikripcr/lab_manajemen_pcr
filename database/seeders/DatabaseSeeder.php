<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\Sys\SysSeeder;
use Database\Seeders\Sys\SysRoleSuperAdminSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SysSeeder::class,
            SysRoleSuperAdminSeeder::class,
            UserSeeder::class,
            LabSeeder::class,
            InventorySeeder::class,
            PengumumanSeeder::class,
            AcademicDataSeeder::class,
        ]);
    }
}
