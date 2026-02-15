<?php
namespace App\Providers;

use App\Models\Notification;
use Carbon\Carbon;
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
    }
}
