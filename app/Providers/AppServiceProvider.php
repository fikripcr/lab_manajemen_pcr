<?php
namespace App\Providers;

use App\Models\Notification;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
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
    }
}
