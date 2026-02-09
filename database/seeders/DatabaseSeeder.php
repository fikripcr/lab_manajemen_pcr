<?php
namespace Database\Seeders;

use Database\Seeders\Hr\HrStatusAktifitasSeeder;
use Database\Seeders\Hr\HrStatusPegawaiSeeder;
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
            \Database\Seeders\Pemutu\PemutuSeeder::class,
            SysRoleSuperAdminSeeder::class,
            // UserSeeder::class,
            // LabSeeder::class,
            // InventorySeeder::class,
            // PengumumanSeeder::class,

            // HR Master Data
            \Database\Seeders\Hr\HrPosisiSeeder::class,
            \Database\Seeders\Hr\HrJabatanFungsionalSeeder::class,
            \Database\Seeders\Hr\HrOrgUnitSeeder::class,
            HrStatusPegawaiSeeder::class,
            HrStatusAktifitasSeeder::class,

            // HR Employee Data
            \Database\Seeders\Hr\HumanCapitalSeeder::class,

            // AcademicDataSeeder::class,
        ]);
    }
}
