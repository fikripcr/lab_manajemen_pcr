<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\AppConfigurationRequest;
use App\Services\Sys\AppConfigService;

class AppConfigController extends Controller
{
    public function __construct(
        protected AppConfigService $appConfigService
    ) {}

    public function index()
    {
        $config = $this->appConfigService->getCurrentConfig();

        return view('pages.sys.app-config.index', compact('config'));
    }

    public function update(AppConfigurationRequest $request)
    {
        $configSection = $request->input('config_section', 'app');
        $data          = $request->validated();

        switch ($configSection) {
            case 'app':
                $this->appConfigService->updateAppConfig($data);
                $sectionName = 'Application';
                break;

            case 'mail':
                $this->appConfigService->updateMailConfig($data);
                $sectionName = 'Mail';
                break;

            case 'google':
                $this->appConfigService->updateGoogleConfig($data);
                $sectionName = 'Google OAuth';
                break;

            case 'backup':
                $this->appConfigService->updateBackupConfig($data);
                $sectionName = 'Database Backup';
                break;

            case 'customization':
                $this->appConfigService->updateThemeConfig($data);
                $sectionName = 'Theme Customization';
                break;

            default:
                return redirect()->back()->with('error', 'Invalid configuration section.');
        }

        return redirect()->back()->with('success', $sectionName . ' configuration updated successfully!');
    }

    public function clearCache()
    {
        $this->appConfigService->clearCache();
        return redirect()->back()->with('success', 'Cache cleared successfully!');
    }

    public function optimize()
    {
        $this->appConfigService->optimize();
        return redirect()->back()->with('success', 'Application optimized successfully!');
    }
}
