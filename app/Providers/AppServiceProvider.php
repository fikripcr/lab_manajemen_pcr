<?php

namespace App\Providers;

use App\Models\Lab;
use App\Models\Notification;
use App\Models\User;
use App\Models\Inventaris;
use App\Models\Pengumuman;
use Illuminate\Notifications\NotificationSender;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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

        // Force HTTPS untuk semua URL
        if (env('APP_ENV') === 'production' || request()->header('X-Forwarded-Proto') == 'https') {
            URL::forceScheme('https');
        }
    }
}
