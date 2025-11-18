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
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
        ];

        return view('pages.admin.app-config.index', compact('config'));
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

        return redirect()->back()->with('success', 'Application configuration updated successfully!');
    }

    public function clearCache()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return redirect()->back()->with('success', 'Cache cleared successfully!');
    }

    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

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
}
