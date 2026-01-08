<?php
namespace App\Services\Sys;

use Exception;
use ZipArchive;

class BackupService
{
    /**
     * Create a backup based on type
     *
     * @param string $type 'files' or 'db'
     * @return string Path to created backup file
     * @throws Exception
     */
    public function createBackup(string $type): string
    {
        return match ($type) {
            'db'    => $this->createDatabaseBackup(),
            'files' => $this->createFileBackup(),
            default => throw new Exception("Invalid backup type: {$type}")
        };
    }

    /**
     * Create a custom file backup
     *
     * @return string Path to backup file
     * @throws Exception
     */
    private function createFileBackup(): string
    {
        $backupDir = storage_path('app/private/backup');
        if (! file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'web_backup_' . date('Y-m-d_H-i-s') . '.zip';
        $fullPath = $backupDir . '/' . $filename;

        $zip = new ZipArchive();
        if (! $zip->open($fullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            throw new Exception("Failed to create zip archive");
        }

        // Directories to include
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

        // Files to include
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

        // Add directories
        foreach ($directories as $directory) {
            if (file_exists($directory)) {
                $this->addDirToZip($zip, $directory, base_path());
            }
        }

        // Add files
        foreach ($files as $file) {
            if (file_exists($file)) {
                $localPath = substr($file, strlen(base_path()) + 1);
                $zip->addFile($file, $localPath);
            }
        }

        $zip->close();

        return $fullPath;
    }

    /**
     * Create a custom database backup
     *
     * @return string Path to backup file
     * @throws Exception
     */
    private function createDatabaseBackup(): string
    {
        $driver   = config('database.default');
        $dbConfig = config("database.connections.{$driver}");

        if (($dbConfig['driver'] ?? null) !== 'mysql') {
            throw new Exception(
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
            throw new Exception("mysqldump tidak ditemukan di: {$mysqldumpPath}. Pastikan path benar di file .env.");
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
            throw new Exception("Backup database gagal. Exit code: {$exitCode}");
        }

        // Compress to ZIP
        $zipFile = str_replace('.sql', '.zip', $fullPath);
        $zip     = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
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
     *
     * @param ZipArchive $zip
     * @param string $dir
     * @param string $baseDir
     * @return void
     */
    private function addDirToZip(ZipArchive $zip, string $dir, string $baseDir): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
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

    /**
     * Get list of all backups
     *
     * @return array
     */
    public function getBackupList(): array
    {
        $backups   = [];
        $backupDir = storage_path('app/private/backup');

        if (! file_exists($backupDir)) {
            return $backups;
        }

        $files = scandir($backupDir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (! preg_match('/^(web_backup_|database_backup_).*\.(zip|sql)$/', $file)) {
                continue;
            }

            $filePath = $backupDir . '/' . $file;
            if (! is_file($filePath)) {
                continue;
            }

            $backups[] = [
                'name'           => 'backup/' . $file,
                'size'           => filesize($filePath),
                'modified'       => filemtime($filePath),
                'formatted_size' => formatBytes(filesize($filePath)),
                'formatted_date' => date('M j, Y g:i A', filemtime($filePath)),
            ];
        }

        // Sort by modification time (newest first)
        usort($backups, fn($a, $b) => $b['modified'] - $a['modified']);

        return $backups;
    }

    /**
     * Get file path for download
     *
     * @param string $filename
     * @return string
     * @throws Exception
     */
    public function getBackupFilePath(string $filename): string
    {
        $filename = normalizePath($filename);

        if (strpos($filename, 'backup/') === 0) {
            $filename = substr($filename, strlen('backup/'));
            $filePath = storage_path('app/private/backup/' . $filename);
        } else {
            $filePath = storage_path('app/private/' . $filename);
        }

        if (! file_exists($filePath)) {
            throw new Exception('Backup file not found: ' . basename($filename));
        }

        return $filePath;
    }

    /**
     * Delete a backup file
     *
     * @param string $filename
     * @return bool
     * @throws Exception
     */
    public function deleteBackup(string $filename): bool
    {
        $filename = normalizePath($filename);

        if (strpos($filename, 'backup/') === 0) {
            $filename = substr($filename, strlen('backup/'));
            $filePath = storage_path('app/private/backup/' . $filename);
        } else {
            $filePath = storage_path('app/private/' . $filename);
        }

        if (! file_exists($filePath)) {
            throw new Exception('Backup file not found: ' . basename($filename));
        }

        return unlink($filePath);
    }
}
