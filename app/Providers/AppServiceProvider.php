<?php
namespace App\Providers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFive();

        // Register Tabler Core view location (allows layouts.tabler.app to work as-is)
        View::addLocation(resource_path('tabler-core/views'));
        // Register Tabler Core Blade Components (x-tabler)
        Blade::anonymousComponentPath(resource_path('tabler-core/views/components/tabler'), 'tabler');

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

        // Custom Route Model Binding: {struktural} → removed

        Relation::morphMap([
            'Perizinan'                => \App\Models\Hr\Perizinan::class,
            'Lembur'                   => \App\Models\Hr\Lembur::class,
            'Pegawai'                  => \App\Models\Hr\Pegawai::class,
            'RiwayatDatadiri'          => \App\Models\Hr\RiwayatDatadiri::class,
            'RiwayatPendidikan'        => \App\Models\Hr\RiwayatPendidikan::class,
            'RiwayatJabatanFungsional' => \App\Models\Hr\RiwayatJabfungsional::class,
            'RiwayatJabatanStruktural' => \App\Models\Hr\RiwayatJabstruktural::class,
            'RiwayatPangkat'           => \App\Models\Hr\RiwayatInpassing::class,
            'RiwayatStruktural'        => \App\Models\Hr\RiwayatJabStruktural::class,
            'PengembanganDiri'         => \App\Models\Hr\PengembanganDiri::class,
        ]);

        // Global Notification Composer
        View::composer('layouts.tabler.header', \App\View\Composers\NotificationComposer::class);
        View::composer('layouts.tabler.header', \App\View\Composers\SiklusSpmiComposer::class);
    }
}
