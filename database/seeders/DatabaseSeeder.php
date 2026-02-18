<?php
namespace Database\Seeders;

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
            MainSysSeeder::class,

            // Modules
            MainHrSeeder::class,
            MainLabSeeder::class,
            MainPemutuSeeder::class,
            MainEofficeSeeder::class,
            MainPmbSeeder::class,
            MainCbtSeeder::class,
            MainSurveiSeeder::class,

            // Shared / Additional
            SlideshowSeeder::class,
            FAQSeeder::class,

            // Optional/Mock
            //MockSeeder::class,
        ]);
    }
}
