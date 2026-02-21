<?php

namespace App\Services\Sys;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AppConfigService
{
    /**
     * Get current configuration values
     */
    public function getCurrentConfig(): array
    {
        return [
            'app_name'                    => config('app.name'),
            'app_debug'                   => $this->getBooleanEnvValue('APP_DEBUG', config('app.debug')),
            'app_url'                     => config('app.url'),
            // Mail configuration
            'mail_mailer'                 => $this->getCurrentEnvValue('MAIL_MAILER'),
            'mail_host'                   => $this->getCurrentEnvValue('MAIL_HOST'),
            'mail_port'                   => $this->getCurrentEnvValue('MAIL_PORT'),
            'mail_username'               => $this->getCurrentEnvValue('MAIL_USERNAME'),
            'mail_password'               => $this->getCurrentEnvValue('MAIL_PASSWORD'),
            'mail_encryption'             => $this->getCurrentEnvValue('MAIL_ENCRYPTION'),
            'mail_from_address'           => $this->getCurrentEnvValue('MAIL_FROM_ADDRESS'),
            'mail_from_name'              => $this->getCurrentEnvValue('MAIL_FROM_NAME'),
            // Google configuration
            'google_client_id'            => $this->getCurrentEnvValue('GOOGLE_CLIENT_ID'),
            'google_client_secret'        => $this->getCurrentEnvValue('GOOGLE_CLIENT_SECRET'),
            'google_redirect_uri'         => $this->getCurrentEnvValue('GOOGLE_REDIRECT_URI'),
            // Mysqldump configuration
            'mysqldump_path'              => $this->getCurrentEnvValue('MYSQLDUMP_PATH'),
            // Theme customization
            'theme_customization_enabled' => $this->getBooleanEnvValue('THEME_CUSTOMIZATION_ENABLED', true),
        ];
    }

    /**
     * Update application configuration
     */
    public function updateAppConfig(array $data): void
    {
        $envPath    = base_path('.env');
        $envContent = File::get($envPath);

        $envContent = $this->updateEnvValue($envContent, 'APP_NAME=', 'APP_NAME=' . $data['app_name']);
        $envContent = $this->updateEnvValue($envContent, 'APP_DEBUG=', 'APP_DEBUG=' . ($data['app_debug'] ? 'true' : 'false'));
        $envContent = $this->updateEnvValue($envContent, 'DEBUGBAR_ENABLED=', 'DEBUGBAR_ENABLED=' . ($data['app_debug'] ? 'true' : 'false'));
        $envContent = $this->updateEnvValue($envContent, 'APP_URL=', 'APP_URL=' . $data['app_url']);

        File::put($envPath, $envContent);
        Artisan::call('config:clear');

        logActivity('config', 'Application configuration updated', auth()->user());
    }

    /**
     * Update mail configuration
     */
    public function updateMailConfig(array $data): void
    {
        $envPath    = base_path('.env');
        $envContent = File::get($envPath);

        $envContent = $this->updateEnvValue($envContent, 'MAIL_MAILER=', 'MAIL_MAILER=' . $data['mail_mailer']);
        $envContent = $this->updateEnvValue($envContent, 'MAIL_HOST=', 'MAIL_HOST=' . $data['mail_host']);
        $envContent = $this->updateEnvValue($envContent, 'MAIL_PORT=', 'MAIL_PORT=' . $data['mail_port']);
        $envContent = $this->updateEnvValue($envContent, 'MAIL_USERNAME=', 'MAIL_USERNAME=' . $data['mail_username']);
        $envContent = $this->updateEnvValue($envContent, 'MAIL_PASSWORD=', 'MAIL_PASSWORD="' . $data['mail_password'] . '"');
        $envContent = $this->updateEnvValue($envContent, 'MAIL_ENCRYPTION=', 'MAIL_ENCRYPTION=' . $data['mail_encryption']);
        $envContent = $this->updateEnvValue($envContent, 'MAIL_FROM_ADDRESS=', 'MAIL_FROM_ADDRESS=' . $data['mail_from_address']);
        $envContent = $this->updateEnvValue($envContent, 'MAIL_FROM_NAME=', 'MAIL_FROM_NAME=' . $data['mail_from_name']);

        File::put($envPath, $envContent);
        Artisan::call('config:clear');

        logActivity('config', 'Mail configuration updated', auth()->user());
    }

    /**
     * Update Google OAuth configuration
     */
    public function updateGoogleConfig(array $data): void
    {
        $envPath    = base_path('.env');
        $envContent = File::get($envPath);

        $envContent = $this->updateEnvValue($envContent, 'GOOGLE_CLIENT_ID=', 'GOOGLE_CLIENT_ID=' . $data['google_client_id']);
        $envContent = $this->updateEnvValue($envContent, 'GOOGLE_CLIENT_SECRET=', 'GOOGLE_CLIENT_SECRET=' . $data['google_client_secret']);
        $envContent = $this->updateEnvValue($envContent, 'GOOGLE_REDIRECT_URI=', 'GOOGLE_REDIRECT_URI=' . $data['google_redirect_uri']);

        File::put($envPath, $envContent);
        Artisan::call('config:clear');

        logActivity('config', 'Google OAuth configuration updated', auth()->user());
    }

    /**
     * Update backup configuration
     */
    public function updateBackupConfig(array $data): void
    {
        $envPath    = base_path('.env');
        $envContent = File::get($envPath);

        $envContent = $this->updateEnvValue($envContent, 'MYSQLDUMP_PATH=', 'MYSQLDUMP_PATH=' . $data['mysqldump_path']);

        File::put($envPath, $envContent);
        Artisan::call('config:clear');

        logActivity('config', 'Database Backup configuration updated', auth()->user());
    }

    /**
     * Update theme customization configuration
     */
    public function updateThemeConfig(array $data): void
    {
        $envPath    = base_path('.env');
        $envContent = File::get($envPath);

        $envContent = $this->updateEnvValue($envContent, 'THEME_CUSTOMIZATION_ENABLED=', 'THEME_CUSTOMIZATION_ENABLED=' . ($data['theme_customization_enabled'] ? 'true' : 'false'));

        File::put($envPath, $envContent);
        Artisan::call('config:clear');

        logActivity('config', 'Theme Customization configuration updated', auth()->user());
    }

    /**
     * Clear application cache
     */
    public function clearCache(): void
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        logActivity('config', 'Application cache cleared: config, cache, views, and routes', auth()->user());
    }

    /**
     * Optimize application
     */
    public function optimize(): void
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        logActivity('config', 'Application optimized: config, routes, and views cached', auth()->user());
    }

    /**
     * Update environment variable value
     */
    private function updateEnvValue($content, $key, $newValue): string
    {
        if (preg_match("/^{$key}.*$/m", $content)) {
            $content = preg_replace("/^{$key}.*$/m", $newValue, $content);
        } else {
            $content .= "\n{$newValue}";
        }

        return $content;
    }

    /**
     * Get current environment value from .env file
     */
    private function getCurrentEnvValue($key, $default = null): ?string
    {
        $envPath = base_path('.env');
        if (! file_exists($envPath)) {
            return $default;
        }

        $content = file_get_contents($envPath);
        $lines   = explode("\n", $content);

        foreach ($lines as $line) {
            if (strpos($line, $key . '=') === 0) {
                $parts = explode('=', $line, 2);
                if (isset($parts[1])) {
                    $value = trim($parts[1]);
                    $length = strlen($value);
                    if (($length >= 2 && substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                        ($length >= 2 && substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                        $value = substr($value, 1, $length - 2);
                    }
                    return $value;
                }
            }
        }

        return $default;
    }

    /**
     * Get boolean environment value
     */
    private function getBooleanEnvValue($key, $default = false): bool
    {
        $value = $this->getCurrentEnvValue($key);

        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
