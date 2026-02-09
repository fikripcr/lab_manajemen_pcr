<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Sys\SysSeeder;
use Database\Seeders\Pemutu\PersonilSeeder;
use Database\Seeders\Hr\HrOrgUnitSeeder;
use Database\Seeders\Lab\InventorySeeder;
use Database\Seeders\Pemutu\PemutuSeeder;
use Database\Seeders\Hr\HrJenisIzinSeeder;
use Database\Seeders\Lab\PengumumanSeeder;
use Database\Seeders\Pemutu\DokumenSeeder;
use Database\Seeders\Hr\HrJenisShiftSeeder;
use Database\Seeders\Hr\HumanCapitalSeeder;
use Database\Seeders\Lab\AcademicDataSeeder;
use Database\Seeders\Pemutu\IndikatorSeeder;
use Database\Seeders\Hr\HrStatusPegawaiSeeder;
use Database\Seeders\Hr\HrStatusAktifitasSeeder;
use Database\Seeders\Sys\SysRoleSuperAdminSeeder;
use Database\Seeders\Hr\HrJabatanFungsionalSeeder;
use Database\Seeders\Hr\HrJenisIndisiplinerSeeder;
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
            // Sys Data
            // SysSeeder::class,
            // SysRoleSuperAdminSeeder::class,
            // UserSeeder::class,

            // // HR Data
            // HrJabatanFungsionalSeeder::class,
            // HrOrgUnitSeeder::class,
            // HrStatusPegawaiSeeder::class,
            // HrStatusAktifitasSeeder::class,
            // HrJenisIzinSeeder::class,
            // HrJenisIndisiplinerSeeder::class,
            // HrJenisShiftSeeder::class,
            // HumanCapitalSeeder::class,

            // // Lab Data
            // AcademicDataSeeder::class,
            // InventorySeeder::class,
            // PengumumanSeeder::class,

            // Pemutu Data
            PemutuSeeder::class,
            DokumenSeeder::class,
            IndikatorSeeder::class,
            PersonilSeeder::class,
        ]);
    }
}
