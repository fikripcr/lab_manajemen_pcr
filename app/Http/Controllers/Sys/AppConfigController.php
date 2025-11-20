<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class AppConfigController extends Controller
{
    public function index()
    {
        $config = [
            'app_name' => config('app.name'),
            'app_env' => $this->getCurrentEnvValue('APP_ENV', config('app.env')),
            'app_debug' => $this->getBooleanEnvValue('APP_DEBUG', config('app.debug')),
            'app_url' => config('app.url'),
            // Mail configuration
            'mail_mailer' => $this->getCurrentEnvValue('MAIL_MAILER'),
            'mail_host' => $this->getCurrentEnvValue('MAIL_HOST'),
            'mail_port' => $this->getCurrentEnvValue('MAIL_PORT'),
            'mail_username' => $this->getCurrentEnvValue('MAIL_USERNAME'),
            'mail_password' => $this->getCurrentEnvValue('MAIL_PASSWORD'),
            'mail_encryption' => $this->getCurrentEnvValue('MAIL_ENCRYPTION'),
            'mail_from_address' => $this->getCurrentEnvValue('MAIL_FROM_ADDRESS'),
            'mail_from_name' => $this->getCurrentEnvValue('MAIL_FROM_NAME'),
            // Google configuration
            'google_client_id' => $this->getCurrentEnvValue('GOOGLE_CLIENT_ID'),
            'google_client_secret' => $this->getCurrentEnvValue('GOOGLE_CLIENT_SECRET'),
            'google_redirect_uri' => $this->getCurrentEnvValue('GOOGLE_REDIRECT_URI'),
            // Mysqldump configuration
            'mysqldump_path' => $this->getCurrentEnvValue('MYSQLDUMP_PATH'),
        ];

        return view('pages.sys.app-config.index', compact('config'));
    }

    public function update(Request $request)
    {
        $configSection = $request->input('config_section', 'app');

        // Define validation rules based on the section
        $validationRules = [];

        switch($configSection) {
            case 'app':
                $validationRules = [
                    'app_name' => 'nullable|string|max:255',
                    'app_env' => 'nullable|in:local,production',
                    'app_debug' => 'nullable|boolean',
                    'app_url' => 'nullable|url',
                ];
                break;
            case 'mail':
                $validationRules = [
                    'mail_mailer' => 'nullable|string',
                    'mail_host' => 'nullable|string',
                    'mail_port' => 'nullable|integer',
                    'mail_username' => 'nullable|string',
                    'mail_password' => 'nullable|string',
                    'mail_encryption' => 'nullable|string',
                    'mail_from_address' => 'nullable|email',
                    'mail_from_name' => 'nullable|string',
                ];
                break;
            case 'google':
                $validationRules = [
                    'google_client_id' => 'nullable|string',
                    'google_client_secret' => 'nullable|string',
                    'google_redirect_uri' => 'nullable|url',
                ];
                break;
            case 'backup':
                $validationRules = [
                    'mysqldump_path' => 'nullable|string',
                ];
                break;
            default:
                return redirect()->back()->with('error', 'Invalid configuration section');
        }

        $request->validate($validationRules);

        // Read the current .env file
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Update values based on the section
        switch($configSection) {
            case 'app':
                $envContent = $this->updateEnvValue($envContent, 'APP_NAME=', 'APP_NAME=' . $request->app_name);
                $envContent = $this->updateEnvValue($envContent, 'APP_ENV=', 'APP_ENV=' . $request->app_env);
                $envContent = $this->updateEnvValue($envContent, 'APP_DEBUG=', 'APP_DEBUG=' . ($request->app_debug ? 'true' : 'false'));
                $envContent = $this->updateEnvValue($envContent, 'DEBUGBAR_ENABLED=', 'DEBUGBAR_ENABLED=' . ($request->app_debug ? 'true' : 'false'));
                $envContent = $this->updateEnvValue($envContent, 'APP_URL=', 'APP_URL=' . $request->app_url);
                break;

            case 'mail':
                $envContent = $this->updateEnvValue($envContent, 'MAIL_MAILER=', 'MAIL_MAILER=' . $request->mail_mailer);
                $envContent = $this->updateEnvValue($envContent, 'MAIL_HOST=', 'MAIL_HOST=' . $request->mail_host);
                $envContent = $this->updateEnvValue($envContent, 'MAIL_PORT=', 'MAIL_PORT=' . $request->mail_port);
                $envContent = $this->updateEnvValue($envContent, 'MAIL_USERNAME=', 'MAIL_USERNAME=' . $request->mail_username);
                $envContent = $this->updateEnvValue($envContent, 'MAIL_PASSWORD=', 'MAIL_PASSWORD=' . $request->mail_password);
                $envContent = $this->updateEnvValue($envContent, 'MAIL_ENCRYPTION=', 'MAIL_ENCRYPTION=' . $request->mail_encryption);
                $envContent = $this->updateEnvValue($envContent, 'MAIL_FROM_ADDRESS=', 'MAIL_FROM_ADDRESS=' . $request->mail_from_address);
                $envContent = $this->updateEnvValue($envContent, 'MAIL_FROM_NAME=', 'MAIL_FROM_NAME=' . $request->mail_from_name);
                break;

            case 'google':
                $envContent = $this->updateEnvValue($envContent, 'GOOGLE_CLIENT_ID=', 'GOOGLE_CLIENT_ID=' . $request->google_client_id);
                $envContent = $this->updateEnvValue($envContent, 'GOOGLE_CLIENT_SECRET=', 'GOOGLE_CLIENT_SECRET=' . $request->google_client_secret);
                $envContent = $this->updateEnvValue($envContent, 'GOOGLE_REDIRECT_URI=', 'GOOGLE_REDIRECT_URI=' . $request->google_redirect_uri);
                break;

            case 'backup':
                $envContent = $this->updateEnvValue($envContent, 'MYSQLDUMP_PATH=', 'MYSQLDUMP_PATH=' . $request->mysqldump_path);
                break;
        }

        // Write the updated content back to .env
        File::put($envPath, $envContent);

        // Clear config cache
        Artisan::call('config:clear');

        // Log the configuration update with appropriate message based on section
        $sectionNames = [
            'app' => 'Application',
            'mail' => 'Mail',
            'google' => 'Google OAuth',
            'backup' => 'Database Backup',
        ];

        $sectionName = $sectionNames[$configSection] ?? 'Configuration';

        logActivity('config', $sectionName . ' configuration updated', auth()->user());

        return redirect()->back()->with('success', $sectionName . ' configuration updated successfully!');
    }

    public function clearCache()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // Log the cache clearing operation
        logActivity('config', 'Application cache cleared: config, cache, views, and routes', auth()->user());

        return redirect()->back()->with('success', 'Cache cleared successfully!');
    }

    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // Log the optimization operation
        logActivity('config', 'Application optimized: config, routes, and views cached', auth()->user());

        return redirect()->back()->with('success', 'Application optimized successfully!');
    }

    private function updateEnvValue($content, $key, $newValue)
    {
        // Check if the key exists in the content
        if (preg_match("/^{$key}.*$/m", $content)) {
            // Update existing value
            $content = preg_replace("/^{$key}.*$/m", $newValue, $content);
        } else {
            // If key doesn't exist, append it to the content
            $content .= "\n{$newValue}";
        }

        return $content;
    }

    /**
     * Get current environment value from .env file
     */
    private function getCurrentEnvValue($key, $default = null)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return $default;
        }

        $content = file_get_contents($envPath);
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            if (strpos($line, $key . '=') === 0) {
                $parts = explode('=', $line, 2);
                if (isset($parts[1])) {
                    $value = trim($parts[1]);
                    // Remove quotes if present
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
    private function getBooleanEnvValue($key, $default = false)
    {
        $value = $this->getCurrentEnvValue($key);

        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
