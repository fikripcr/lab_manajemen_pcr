<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

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
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            // Log the error to the ErrorLog model
            logError($e, 'error', [
                'backup_type' => $type,
                'description' => 'Error during backup creation',
            ]);

            $errorMessage = $e->getMessage();
            // Clean the error message to remove newlines for JSON response
            $cleanErrorMessage = trim(str_replace(["\n", "\r"], " ", $errorMessage));

            // Return JSON response for AJAX
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create backup: ' . $cleanErrorMessage,
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
        if (! file_exists($backupDir)) {
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
     * Create a custom database backup using spatie/laravel-backup
     */
    private function createDatabaseBackup()
    {
        // Pastikan hanya MySQL yang didukung
        $driver   = config('database.default');
        $dbConfig = config("database.connections.{$driver}");

        if (($dbConfig['driver'] ?? null) !== 'mysql') {
            throw new \Exception(
                'Database backup hanya didukung untuk MySQL. ' .
                'Driver saat ini: ' . ($dbConfig['driver'] ?? 'unknown')
            );
        }

        $backupDir = storage_path('app/private/backup');
        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $fullPath = $backupDir . '/' . $filename;

        $mysqldumpPath = env('MYSQLDUMP_PATH', 'C:/laragon/bin/mysql/mysql-8.0.30-winx64/bin/mysqldump.exe');

        if (! file_exists($mysqldumpPath)) {
            throw new \Exception("mysqldump tidak ditemukan di: {$mysqldumpPath}. Pastikan path benar di file .env.");
        }

        $command = '"' . $mysqldumpPath . '" ' .
        '--host=' . escapeshellarg($dbConfig['host']) . ' ' .
        '--port=' . escapeshellarg($dbConfig['port'] ?? 3306) . ' ' .
        '--user=' . escapeshellarg($dbConfig['username']) . ' ' .
        '--password=' . escapeshellarg($dbConfig['password']) . ' ' .
        escapeshellarg($dbConfig['database']) . ' ' .
        '> ' . escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, $fullPath));

        $exitCode = 0;
        exec($command, $output, $exitCode);

        if ($exitCode !== 0) {
            throw new \Exception("Backup database gagal. Exit code: {$exitCode}");
        }

        // Opsional: kompres ke ZIP
        $zipFile = str_replace('.sql', '.zip', $fullPath);
        $zip     = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $zip->addFile($fullPath, basename($fullPath));
            $zip->close();
            unlink($fullPath);
            $fullPath = $zipFile;
        }

        \Log::info("Backup database berhasil: {$fullPath}");
        return $fullPath;
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
        $filename = normalizePath($filename);

        // Check if it's in the backup directory
        if (strpos($filename, 'backup/') === 0) {
            $filename = substr($filename, strlen('backup/'));
            $filePath = storage_path('app/private/backup/' . $filename);
        } else {
            // Legacy path for existing backups
            $filePath = storage_path('app/private/' . $filename);
        }

        if (! file_exists($filePath)) {
            abort(404, 'Backup file not found: ' . $filename);
        }

        return response()->download($filePath);
    }

    public function delete($filename)
    {
        // Normalize the path to prevent directory traversal
        $filename = normalizePath($filename);

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

        return redirect()->back()
            ->with('success', 'Backup deleted successfully');
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
                            'name'           => 'backup/' . $file,
                            'size'           => filesize($filePath),
                            'modified'       => filemtime($filePath),
                            'formatted_size' => formatBytes(filesize($filePath)),
                            'formatted_date' => date('M j, Y g:i A', filemtime($filePath)),
                        ];
                    }
                }
            }
        }

        // Sort by modification time (newest first)
        usort($backups, function ($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return $backups;
    }

}
