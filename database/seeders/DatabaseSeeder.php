<?php
namespace Database\Seeders;

use Database\Seeders\Hr\HrOrgStructureSeeder;
use Database\Seeders\Hr\HrStatusAktifitasSeeder;
use Database\Seeders\Hr\HrStatusPegawaiSeeder;
use Database\Seeders\Hr\HumanCapitalSeeder;
use Database\Seeders\Hr\LegacyHrDataSeeder;
use Database\Seeders\Lab\AcademicDataSeeder;
use Database\Seeders\Lab\InventorySeeder;
use Database\Seeders\Lab\LabSeeder;
use Database\Seeders\Lab\PengumumanSeeder;
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

            // HR Master Data
            LegacyHrDataSeeder::class,
            HrOrgStructureSeeder::class,
            HrStatusPegawaiSeeder::class,
            HrStatusAktifitasSeeder::class,

            // HR Employee Data
            HumanCapitalSeeder::class,

            AcademicDataSeeder::class,
        ]);
    }
}
