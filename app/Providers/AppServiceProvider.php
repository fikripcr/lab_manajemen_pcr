<?php

namespace App\Providers;

use App\Models\Lab;
use App\Models\User;
use App\Models\Inventaris;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Route;
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
        // Force HTTPS untuk semua URL
        if (env('APP_ENV') === 'production' || request()->header('X-Forwarded-Proto') == 'https') {
            URL::forceScheme('https');
        }
    }
}
