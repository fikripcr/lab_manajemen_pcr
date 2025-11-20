<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\ServerMonitor\Models\Check;

class ServerMonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:monitor {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor server disk space and database size';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type') ?? 'all';

        if ($type === 'all' || $type === 'diskspace') {
            $this->checkDiskSpace();
        }

        if ($type === 'all' || $type === 'databasesize') {
            $this->checkDatabaseSize();
        }

        if ($type === 'all' || $type === 'projectsize') {
            $this->checkProjectSize();
        }

        $this->info('Server monitoring completed.');
    }

    /**
     * Check disk space
     */
    protected function checkDiskSpace()
    {
        // Get disk space information
        $diskTotalSpace = disk_total_space(base_path());
        $diskFreeSpace = disk_free_space(base_path());
        $diskUsedSpace = $diskTotalSpace - $diskFreeSpace;
        $diskUsagePercentage = round(($diskUsedSpace / $diskTotalSpace) * 100, 2);

        // Format sizes to human readable format
        $totalSpaceFormatted = formatBytes($diskTotalSpace);
        $usedSpaceFormatted = formatBytes($diskUsedSpace);
        $freeSpaceFormatted = formatBytes($diskFreeSpace);

        // Store in server monitor checks
        $this->updateOrCreateCheck('diskspace', [
            'latest_human_output' => "Used: {$usedSpaceFormatted}/{$totalSpaceFormatted} ({$diskUsagePercentage}%)",
            'output' => json_encode([
                'total_space' => $diskTotalSpace,
                'used_space' => $diskUsedSpace,
                'free_space' => $diskFreeSpace,
                'usage_percentage' => $diskUsagePercentage,
                'total_space_formatted' => $totalSpaceFormatted,
                'used_space_formatted' => $usedSpaceFormatted,
                'free_space_formatted' => $freeSpaceFormatted,
            ]),
        ]);
    }

    /**
     * Check database size
     */
    protected function checkDatabaseSize()
    {
        $databaseConnection = config('database.default');
        $databaseName = config("database.connections.{$databaseConnection}.database");
        $databaseUsername = config("database.connections.{$databaseConnection}.username");
        $databasePassword = config("database.connections.{$databaseConnection}.password");
        $databaseHost = config("database.connections.{$databaseConnection}.host");
        $databasePort = config("database.connections.{$databaseConnection}.port");

        // Get database size based on database type
        $databaseSize = 0;
        $databaseSizeFormatted = '0 B';

        if (config("database.connections.{$databaseConnection}.driver") === 'mysql') {
            $databaseSize = DB::select("
                SELECT SUM(data_length + index_length) as size
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$databaseName])[0]->size;

            $databaseSizeFormatted = formatBytes($databaseSize);
        }

        // Store in server monitor checks
        $this->updateOrCreateCheck('databasesize', [
            'latest_human_output' => "Database size: {$databaseSizeFormatted}",
            'output' => json_encode([
                'database_name' => $databaseName,
                'size_bytes' => $databaseSize,
                'size_formatted' => $databaseSizeFormatted,
            ]),
        ]);
    }

    /**
     * Update or create a check record
     */
    protected function updateOrCreateCheck($type, $data)
    {
        // Get or create a host for this application
        $host = \App\Models\Sys\ServerMonitorHost::firstOrCreate(
            ['name' => config('app.name') . ' - Local Server'],
            [
                'name' => config('app.name') . ' - Local Server',
                'ssh_user' => null,
                'port' => null,
                'ip' => null,
            ]
        );

        // Get or create the check
        $check = \App\Models\Sys\ServerMonitorCheck::firstOrCreate(
            ['host_id' => $host->id, 'type' => $type],
            [
                'host_id' => $host->id,
                'type' => $type,
                'status' => 'success', // Set to success as we're monitoring manually
                'enabled' => true,
            ]
        );

        // Update the check with new information
        $check->update([
            'status' => 'success',
            'last_run_message' => $data['latest_human_output'],
            'last_run_output' => $data['output'],
            'last_ran_at' => now(),
        ]);
    }

    /**
     * Check project size
     */
    protected function checkProjectSize()
    {
        // Get detailed breakdown of project components
        $appsSize = $this->calculateAppsSize();
        $storageSize = $this->calculateBackupSize();
        $logSize = $this->calculateLogSize();
        $uploadsSize = $this->calculateUploadsSize();
        $vendorSize = $this->calculateVendorSize();
        $nodeModulesSize = $this->calculateNodeModulesSize();

        // Calculate total project size (excluding heavy directories like node_modules, vendor)
        $projectSize = $appsSize + $storageSize + $logSize + $uploadsSize;
        $totalWithHeavyDirs = $projectSize + $vendorSize + $nodeModulesSize;

        $projectSizeFormatted = formatBytes($projectSize);
        $totalWithHeavyDirsFormatted = formatBytes($totalWithHeavyDirs);

        // Store in server monitor checks
        $this->updateOrCreateCheck('projectsize', [
            'latest_human_output' => "Project breakdown: Apps={".formatBytes($appsSize)."}, Storage={".formatBytes($storageSize)."}, Logs={".formatBytes($logSize)."}, Uploads={".formatBytes($uploadsSize)."}",
            'output' => json_encode([
                'project_path' => base_path(),
                'size_bytes' => $projectSize,
                'size_formatted' => $projectSizeFormatted,
                'total_size_with_heavy_dirs' => $totalWithHeavyDirs,
                'total_size_with_heavy_dirs_formatted' => $totalWithHeavyDirsFormatted,
                'apps_size' => [
                    'size_bytes' => $appsSize,
                    'size_formatted' => formatBytes($appsSize),
                ],
                'storage_size' => [  // This represents backup size now
                    'size_bytes' => $storageSize,
                    'size_formatted' => formatBytes($storageSize),
                ],
                'log_size' => [
                    'size_bytes' => $logSize,
                    'size_formatted' => formatBytes($logSize),
                ],
                'uploads_size' => [
                    'size_bytes' => $uploadsSize,
                    'size_formatted' => formatBytes($uploadsSize),
                ],
                'vendor_size' => [
                    'size_bytes' => $vendorSize,
                    'size_formatted' => formatBytes($vendorSize),
                ],
                'node_modules_size' => [
                    'size_bytes' => $nodeModulesSize,
                    'size_formatted' => formatBytes($nodeModulesSize),
                ],
            ]),
        ]);
    }

    /**
     * Calculate the size of main application directories
     */
    private function calculateAppsSize()
    {
        $dirs = [
            base_path('app'),
            base_path('config'),
            base_path('database'),
            base_path('public'),
            base_path('resources'),
            base_path('routes'),
            base_path('bootstrap'),
            base_path('tests'),
            base_path('.env'),
            base_path('.env.example'),
            base_path('artisan'),
            base_path('composer.json'),
            base_path('composer.lock'),
            base_path('package.json'),
            base_path('package-lock.json'),
            base_path('phpunit.xml'),
            base_path('README.md'),
            base_path('server.php'),
            base_path('tailwind.config.js'),
            base_path('vite.config.js'),
        ];

        $size = 0;
        foreach ($dirs as $dir) {
            if (file_exists($dir)) {
                if (is_file($dir)) {
                    $size += filesize($dir);
                } else {
                    $size += $this->calculateDirectorySize($dir, []);
                }
            }
        }
        return $size;
    }

    /**
     * Calculate the size of backup directory
     */
    private function calculateBackupSize()
    {
        $backupDir = storage_path('app/private/backup');
        if (file_exists($backupDir)) {
            return $this->calculateDirectorySize($backupDir, []);
        }
        return 0;
    }

    /**
     * Calculate the size of logs
     */
    private function calculateLogSize()
    {
        $dir = storage_path('logs');
        if (file_exists($dir)) {
            return $this->calculateDirectorySize($dir, []);
        }
        return 0;
    }

    /**
     * Calculate the size of uploaded files
     */
    private function calculateUploadsSize()
    {
        $dirs = [
            storage_path('app/public'), // Public uploads
            storage_path('app/private'), // Private files
        ];

        $size = 0;
        foreach ($dirs as $dir) {
            if (file_exists($dir)) {
                $size += $this->calculateDirectorySize($dir, []);
            }
        }
        return $size;
    }

    /**
     * Calculate the size of vendor directory
     */
    private function calculateVendorSize()
    {
        $dir = base_path('vendor');
        if (file_exists($dir)) {
            return $this->calculateDirectorySize($dir, []);
        }
        return 0;
    }

    /**
     * Calculate the size of node_modules directory
     */
    private function calculateNodeModulesSize()
    {
        $dir = base_path('node_modules');
        if (file_exists($dir)) {
            return $this->calculateDirectorySize($dir, []);
        }
        return 0;
    }

    /**
     * Calculate directory size recursively
     * @param string $path Directory path to calculate size for
     * @param array $excludedDirs Directories to exclude from calculation
     * @return int Size in bytes
     */
    private function calculateDirectorySize($path, $excludedDirs = [])
    {
        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            // Skip if the file is in an excluded directory
            $filePath = $file->getPathname();
            $isExcluded = false;

            foreach ($excludedDirs as $excludedDir) {
                if (strpos($filePath, $excludedDir) !== false) {
                    $isExcluded = true;
                    break;
                }
            }

            if (!$isExcluded && $file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }
}
