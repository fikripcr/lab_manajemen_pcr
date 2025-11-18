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
        ];

        return view('pages.sys.app-config.index', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_env' => 'required|in:local,production',
            'app_debug' => 'nullable|boolean',
            'app_url' => 'required|url',
        ]);

        // Read the current .env file
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Update the values
        $envContent = $this->updateEnvValue($envContent, 'APP_NAME=', 'APP_NAME=' . $request->app_name);
        $envContent = $this->updateEnvValue($envContent, 'APP_ENV=', 'APP_ENV=' . $request->app_env);
        $envContent = $this->updateEnvValue($envContent, 'APP_DEBUG=', 'APP_DEBUG=' . ($request->app_debug ? 'true' : 'false'));
        $envContent = $this->updateEnvValue($envContent, 'DEBUGBAR_ENABLED=', 'DEBUGBAR_ENABLED=' . ($request->app_debug ? 'true' : 'false'));
        $envContent = $this->updateEnvValue($envContent, 'APP_URL=', 'APP_URL=' . $request->app_url);

        // Write the updated content back to .env
        File::put($envPath, $envContent);

        // Clear config cache
        Artisan::call('config:clear');

        // Log the configuration update
        activity()
            ->performedOn(auth()->user())
            ->causedBy(auth()->user())
            ->log('Application configuration updated: app_name=' . $request->app_name . ', app_env=' . $request->app_env . ', app_debug=' . ($request->app_debug ? 'true' : 'false') . ', app_url=' . $request->app_url);

        return redirect()->back()->with('success', 'Application configuration updated successfully!');
    }

    public function clearCache()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // Log the cache clearing operation
        activity()
            ->performedOn(auth()->user())
            ->causedBy(auth()->user())
            ->log('Application cache cleared: config, cache, views, and routes');

        return redirect()->back()->with('success', 'Cache cleared successfully!');
    }

    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // Log the optimization operation
        activity()
            ->performedOn(auth()->user())
            ->causedBy(auth()->user())
            ->log('Application optimized: config, routes, and views cached');

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
