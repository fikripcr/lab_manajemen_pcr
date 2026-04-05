<?php

namespace Database\Seeders\Sys;

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
            // Core System
            \Database\Seeders\Sys\MainSysSeeder::class,
            \Database\Seeders\Sys\RolePermissionSysSeeder::class,

            // Permission Seeders (all modules)
            \Database\Seeders\Pemutu\RolePermissionPemutuSeeder::class,
            \Database\Seeders\Hr\RolePermissionHrSeeder::class,
            \Database\Seeders\Lab\RolePermissionLabSeeder::class,
            \Database\Seeders\Eoffice\RolePermissionEofficeSeeder::class,
            \Database\Seeders\Pmb\RolePermissionPmbSeeder::class,
            \Database\Seeders\Cbt\RolePermissionCbtSeeder::class,
            \Database\Seeders\Project\RolePermissionSurveiSeeder::class,
            \Database\Seeders\Project\RolePermissionProjectSeeder::class,
            \Database\Seeders\Project\RolePermissionKegiatanSeeder::class,

            // Module Data Seeders
            \Database\Seeders\Hr\MainHrSeeder::class,
            \Database\Seeders\Hr\SyncPegawaiCommonColumnsSeeder::class,
            \Database\Seeders\Lab\MainLabSeeder::class,
            \Database\Seeders\Pemutu\MainPemutuSeeder::class,
            \Database\Seeders\Pemutu\Spmi20252026Seeder::class,
            \Database\Seeders\Eoffice\MainEofficeSeeder::class,
            \Database\Seeders\Pmb\MainPmbSeeder::class,
            \Database\Seeders\Cbt\MainCbtSeeder::class,
            \Database\Seeders\Project\MainSurveiSeeder::class,
            \Database\Seeders\Project\MainProjectSeeder::class,

            // CMS / Shared
            \Database\Seeders\Cms\SlideshowSeeder::class,
            \Database\Seeders\Cms\FAQSeeder::class,
            \Database\Seeders\Cms\LabelSeeder::class,

            // Optional/Mock (uncomment if needed)
            // \Database\Seeders\Cms\MockSeeder::class,
        ]);
    }
}
