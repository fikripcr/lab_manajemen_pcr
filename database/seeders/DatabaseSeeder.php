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
            SysRoleSuperAdminSeeder::class,
            UserSeeder::class,

            // HR Master Data
            \Database\Seeders\Hr\HrJabatanFungsionalSeeder::class,
            \Database\Seeders\Hr\HrOrgUnitSeeder::class,
            HrStatusPegawaiSeeder::class,
            HrStatusAktifitasSeeder::class,
            \Database\Seeders\Hr\HrJenisIzinSeeder::class,
            \Database\Seeders\Hr\HrJenisIndisiplinerSeeder::class,
            \Database\Seeders\Hr\HrJenisShiftSeeder::class,

            // HR Employee Data
            \Database\Seeders\Hr\HumanCapitalSeeder::class,

            // Lab Data
            \Database\Seeders\Lab\AcademicDataSeeder::class,

            // Pemutu Data
            \Database\Seeders\Pemutu\PemutuSeeder::class,
        ]);
    }
}
