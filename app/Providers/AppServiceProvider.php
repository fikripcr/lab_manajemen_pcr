<?php
namespace App\Providers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/EofficeHelper.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Register the custom Notification model
        $this->app->bind('Illuminate\Notifications\DatabaseNotification', function () {
            return new Notification();
        });

        Sanctum::usePersonalAccessTokenModel(\App\Models\Sys\PersonalAccessToken::class);

        // Force HTTPS untuk semua URL
        // if (env('APP_ENV') === 'production' || request()->header('X-Forwarded-Proto') == 'https') {
        //     URL::forceScheme('https');
        // }

        // Set locale for Carbon (Native Localization)
        Carbon::setLocale(env('APP_LOCALE', 'en'));

        Relation::morphMap([
            'Perizinan'                => \App\Models\Hr\Perizinan::class,
            'Lembur'                   => \App\Models\Hr\Lembur::class,
            'Pegawai'                  => \App\Models\Hr\Pegawai::class,
            'RiwayatDatadiri'          => \App\Models\Hr\RiwayatDatadiri::class,
            'RiwayatPendidikan'        => \App\Models\Hr\RiwayatPendidikan::class,
            'RiwayatJabatanFungsional' => \App\Models\Hr\RiwayatJabfungsional::class,
            'RiwayatJabatanStruktural' => \App\Models\Hr\RiwayatJabstruktural::class,
            'RiwayatPangkat'           => \App\Models\Hr\RiwayatInpassing::class, // Assuming Pangkat is Inpassing based on schema
            'RiwayatPenugasan'         => \App\Models\Hr\RiwayatPenugasan::class,
            'PengembanganDiri'         => \App\Models\Hr\PengembanganDiri::class,
        ]);
    }
}
