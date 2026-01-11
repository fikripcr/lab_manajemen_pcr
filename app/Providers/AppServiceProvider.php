<?php
namespace App\Providers;

use App\Helpers\ThemeHelper;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share Layout Data Globally (Replaces InjectLayoutData Middleware)
        View::share('layoutData', ThemeHelper::getLayoutData());

        // Share Theme Data (Legacy compatibility for settings view)
        View::share('themeData', [
            'theme'                => ThemeHelper::get('theme'),
            'themePrimary'         => ThemeHelper::get('theme-primary'),
            'themeFont'            => ThemeHelper::get('theme-font'),
            'themeBase'            => ThemeHelper::get('theme-base'),
            'themeRadius'          => ThemeHelper::get('theme-radius'),
            'themeBg'              => ThemeHelper::get('theme-bg'),
            'themeSidebarBg'       => ThemeHelper::get('theme-sidebar-bg'),
            'themeHeaderTopBg'     => ThemeHelper::get('theme-header-top-bg'),
            'themeHeaderOverlapBg' => ThemeHelper::get('theme-header-overlap-bg'),
            'themeHeaderSticky'    => ThemeHelper::get('theme-header-sticky'),
            'themeCardStyle'       => ThemeHelper::get('theme-card-style'),
            'themeBoxedBg'         => ThemeHelper::get('theme-boxed-bg'),
            // Auth specific (defaults for now as they aren't fully in helper/cookie yet)
            'authLayout'           => ThemeHelper::get('auth-layout', 'basic'),
            'authFormPosition'     => ThemeHelper::get('auth-form-position', 'left'),
        ]);

        Paginator::useBootstrapFive();

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
