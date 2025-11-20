<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Exception;

class BackupController extends Controller
{
    public function index()
    {
        $backups = $this->getBackupList();

        return view('pages.sys.backup.index', compact('backups'));
    }

    public function create(Request $request)
    {
        $type = $request->input('type', 'files'); // files, db

        try {
            if ($type === 'db') {
                // Use custom implementation for database backup
                $this->createDatabaseBackup();
            } elseif ($type === 'files') {
                // Use custom implementation for file backup
                $this->createFileBackup();
            }

            $message = "Backup created successfully";

                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            // Clean the error message to remove newlines for JSON response
            $cleanErrorMessage = trim(str_replace(["\n", "\r"], " ", $errorMessage));

            // Return JSON response for AJAX
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create backup: ' . $cleanErrorMessage
                ], 500);
            }

            // For non-AJAX requests, redirect back with error message
            return redirect()->route('pages.sys.backup.index')
                ->with('error', 'Failed to create backup: ' . $errorMessage);
        }
    }

    /**
     * Create a custom file backup
     */
    private function createFileBackup()
    {
        // Create backup directory
        $backupDir = storage_path('app/private/backup');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Create a unique filename with timestamp
        $filename = 'web_backup_' . date('Y-m-d_H-i-s') . '.zip';
        $fullPath = $backupDir . '/' . $filename;

        // Initialize the zip archive
        $zip = new \ZipArchive();
        $zip->open($fullPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Directories to include in the backup (excluding unnecessary ones)
        $directories = [
            base_path('app'),
            base_path('bootstrap'),
            base_path('config'),
            base_path('database'),
            base_path('public'),
            base_path('resources'),
            base_path('routes'),
            base_path('tests'),
        ];

        // Files to include in the backup
        $files = [
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

        // Add directories to the zip
        foreach ($directories as $directory) {
            if (file_exists($directory)) {
                $this->addDirToZip($zip, $directory, base_path());
            }
        }

        // Add specific files to the zip
        foreach ($files as $file) {
            if (file_exists($file)) {
                // Get relative path from base_path
                $localPath = substr($file, strlen(base_path()) + 1);
                $zip->addFile($file, $localPath);
            }
        }

        $zip->close();
    }

    /**
     * Create a custom database backup
     */
    private function createDatabaseBackup()
    {
        // Create backup directory
        $backupDir = storage_path('app/private/backup');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Create a unique filename with timestamp
        $filename = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $fullPath = $backupDir . '/' . $filename;

        // Get mysqldump path from environment, with fallback
        $mysqldumpPath = env('MYSQLDUMP_PATH', 'C:/laragon/bin/mysql/mysql-8.0.30-winx64/bin/mysqldump.exe');

        // Check if mysqldump path is set and executable exists
        if (empty($mysqldumpPath)) {
            throw new Exception('Mysqldump path is not set in configuration. Please set it in the App Configuration.');
        }

        // For Windows, check if the file exists
        if (PHP_OS_FAMILY === 'Windows' && !file_exists($mysqldumpPath)) {
            throw new Exception("Mysqldump executable not found at: {$mysqldumpPath}. Please verify the path in App Configuration.");
        }

        // Get database connection details from environment
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        // Build the mysqldump command with proper executable path for Laragon
        $mysqldumpCmd = $mysqldumpPath;

        // For Windows systems, wrap the path in quotes if it contains spaces
        if (PHP_OS_FAMILY === 'Windows' && strpos($mysqldumpPath, ' ') !== false) {
            $mysqldumpCmd = '"' . $mysqldumpPath . '"';
        }

        // For Windows, we'll use the full path to mysqldump
        $command = sprintf(
            '%s --host=%s --port=%s --user=%s --password=%s %s > "%s"',
            $mysqldumpCmd,
            $host,
            $port,
            $username,
            $password,
            $database,
            $fullPath
        );

        // Execute the command using cmd on Windows
        $output = [];
        $returnCode = 0;

        // For Windows systems
        if (PHP_OS_FAMILY === 'Windows') {
            $command = str_replace('\\', '/', $command);
            exec('cmd /c ' . escapeshellcmd($command) . ' 2>&1', $output, $returnCode);
        } else {
            // For Unix-like systems
            exec(escapeshellcmd($command) . ' 2>&1', $output, $returnCode);
        }

        if ($returnCode !== 0) {
            // If the specific path doesn't work, try generic mysqldump
            $genericMysqldumpPath = 'mysqldump';
            $genericCommand = sprintf(
                '%s --host=%s --port=%s --user=%s --password=%s %s > "%s"',
                $genericMysqldumpPath,
                $host,
                $port,
                $username,
                $password,
                $database,
                $fullPath
            );

            $output = [];
            $returnCode = 0;
            if (PHP_OS_FAMILY === 'Windows') {
                exec('cmd /c ' . escapeshellcmd($genericCommand) . ' 2>&1', $output, $returnCode);
            } else {
                exec(escapeshellcmd($genericCommand) . ' 2>&1', $output, $returnCode);
            }

            if ($returnCode !== 0) {
                throw new Exception('Database backup failed with return code: ' . $returnCode . '. Output: ' . implode(' ', $output) . '. Please verify your Mysqldump path in App Configuration.');
            }
        }
    }

    /**
     * Add a directory to the zip file recursively
     */
    private function addDirToZip($zip, $dir, $baseDir)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            // Skip vendor, node_modules, and other unnecessary directories
            $relativePath = substr($file->getPathname(), strlen($baseDir) + 1);

            // Skip certain directories
            if (preg_match('/(vendor|node_modules|\.git|storage\/app|storage\/framework|storage\/logs)/', $relativePath)) {
                continue;
            }

            if ($file->isFile()) {
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }
    }

    public function download($filename)
    {
        // Normalize the path to prevent directory traversal
        $filename = $this->normalizePath($filename);

        // Check if it's in the backup directory
        if (strpos($filename, 'backup/') === 0) {
            $filename = substr($filename, strlen('backup/'));
            $filePath = storage_path('app/private/backup/' . $filename);
        } else {
            // Legacy path for existing backups
            $filePath = storage_path('app/private/' . $filename);
        }

        if (!file_exists($filePath)) {
            abort(404, 'Backup file not found: ' . $filename);
        }

        return response()->download($filePath);
    }

    public function delete($filename)
    {
        // Normalize the path to prevent directory traversal
        $filename = $this->normalizePath($filename);

        // Check if it's in the backup directory
        if (strpos($filename, 'backup/') === 0) {
            $filename = substr($filename, strlen('backup/'));
            $filePath = storage_path('app/private/backup/' . $filename);
        } else {
            // Legacy path for existing backups
            $filePath = storage_path('app/private/' . $filename);
        }

        if (file_exists($filePath)) {
            unlink($filePath);
        } else {
            abort(404, 'Backup file not found: ' . $filename);
        }

        return redirect()->route('pages.sys.backup.index')
            ->with('success', 'Backup deleted successfully');
    }

    private function normalizePath($path)
    {
        // Clean up the path to prevent directory traversal attacks
        $path = str_replace(['../', '..\\', './', '.\\'], '', $path);
        return $path;
    }

    private function getBackupList()
    {
        $backups = [];

        // Get all backup files from the backup directory
        $backupDir = storage_path('app/private/backup');
        if (file_exists($backupDir)) {
            $files = scandir($backupDir);

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' &&
                    (preg_match('/^web_backup_.*\.(zip|sql)$/', $file) ||
                     preg_match('/^database_backup_.*\.(zip|sql)$/', $file))) {
                    $filePath = $backupDir . '/' . $file;
                    if (is_file($filePath)) {
                        $backups[] = [
                            'name' => 'backup/' . $file,
                            'size' => filesize($filePath),
                            'modified' => filemtime($filePath),
                            'formatted_size' => $this->formatBytes(filesize($filePath)),
                            'formatted_date' => date('M j, Y g:i A', filemtime($filePath))
                        ];
                    }
                }
            }
        }

        // Sort by modification time (newest first)
        usort($backups, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return $backups;
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }
}
