<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Sys\ServerMonitorHost;
use Spatie\ServerMonitor\Models\Check;
use Illuminate\Support\Facades\Storage;

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
        $host = ServerMonitorHost::firstOrCreate(
            ['name' => config('app.name') . ' - Local Server'],
            [
                'name' => config('app.name') . ' - Local Server',
                'ssh_user' => null,
                'port' => null,
                'ip' => null,
            ]
        );

        // Get or create the check
        $check = ServerMonitorCheck::firstOrCreate(
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
}
