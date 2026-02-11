<?php
namespace Database\Seeders;

use Database\Seeders\Pemutu\DokumenSeeder;
use Database\Seeders\Pemutu\IndikatorSeeder;
use Database\Seeders\Pemutu\PemutuSeeder;
use Database\Seeders\Pemutu\PersonilSeeder;
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
            Eoffice\EofficeSeeder::class,
        ]);
    }
}
