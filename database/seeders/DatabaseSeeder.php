<?php
namespace Database\Seeders;

use Database\Seeders\Sys\SysRoleSuperAdminSeeder;
use Database\Seeders\Sys\SysSeeder;
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
            SysSeeder::class,
            PemtuSeeder::class,
            SysRoleSuperAdminSeeder::class,
            UserSeeder::class,
            LabSeeder::class,
            InventorySeeder::class,
            PengumumanSeeder::class,
            AcademicDataSeeder::class,
        ]);
    }
}
