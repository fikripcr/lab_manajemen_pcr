<?php

if (! function_exists('encryptId')) {
    /**
     * Encrypt an ID using Hashids
     *
     * @param int $id
     * @return string
     */
    function encryptId($id)
    {
        return app('hashids')->encode($id);
    }
}

if (! function_exists('decryptId')) {
    /**
     * Decrypt a Hashid to get the original ID
     *
     * @param string $hash
     * @param bool $throwException Whether to throw exception on failure
     * @return int|null
     */
    function decryptId($hash, $throwException = true)
    {
        if (! $hash) {
            if ($throwException) {
                abort(403, 'Data tidak ditemukan.');
            }
            return null;
        }

        $decoded = app('hashids')->decode($hash);

        if (empty($decoded)) {
            if ($throwException) {
                abort(403, 'Data tidak ditemukan.');
            }
            return null;
        }

        return $decoded[0];
    }
}

if (! function_exists('decryptIdIfEncrypted')) {
    /**
     * Decrypt an ID if it's a hashid, otherwise return it as is if it's numeric.
     *
     * @param mixed $id
     * @param bool $throwException
     * @return int|null
     */
    function decryptIdIfEncrypted($id, $throwException = true)
    {
        if (is_numeric($id)) {
            return (int) $id;
        }

        return decryptId($id, $throwException);
    }
}

if (! function_exists('logActivity')) {
    function logActivity($logName, $description, $subject = null, $properties = [])
    {
        // Get the current user
        $causer = auth()->user();

        // Get IP address and user agent from the request
        $request   = request();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Create activity log with specified logName
        $activity = activity($logName);

        if ($subject) {
            $activity->performedOn($subject);
        }

        if ($causer) {
            $activity->causedBy($causer);
        }

        // Add IP and user agent to properties
        $properties = array_merge($properties, [
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'url'        => $request->fullUrl(),
            'method'     => $request->method(),
            'user_id'    => $causer?->id,
            'user_name'  => $causer?->name,
        ]);

        $activity->withProperties($properties)->log($description);
    }
}

if (! function_exists('normalizePath')) {
    /**
     * Clean up the path to prevent directory traversal attacks
     *
     * @param string $path
     * @return string
     */
    function normalizePath($path)
    {
        // Clean up the path to prevent directory traversal attacks
        $path = str_replace(['../', '..\\', './', '.\\'], '', $path);
        return $path;
    }
}

if (! function_exists('formatBytes')) {
    /**
     * Format bytes to human readable format
     *
     * @param int $size Size in bytes
     * @param int $precision Number of decimal places
     * @return string Formatted size with unit
     */
    function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }
}

if (! function_exists('logError')) {
    /**
     * Log an error directly to the ErrorLog model
     *
     * @param \Throwable|string $error
     * @param string $level
     * @param array $context Additional context information
     * @return \App\Models\Sys\ErrorLog|null
     */
    function logError($error, $level = 'error', $context = [])
    {
        try {
            // Handle both exception objects and string messages
            $exception = $error;
            if (! $error instanceof \Throwable) {
                $exception = new \Exception($error);
            }

            // Extract additional context data from exception if it's a database exception
            $additionalContext = [];

            if ($exception instanceof \PDOException  || $exception instanceof \Illuminate\Database\QueryException) {
                if (isset($exception->errorInfo) && is_array($exception->errorInfo)) {
                    $additionalContext = [
                        'error_info' => [
                            'SQLSTATE'       => $exception->errorInfo[0] ?? null,
                            'Driver Code'    => $exception->errorInfo[1] ?? null,
                            'Driver Message' => $exception->errorInfo[2] ?? null,
                        ],
                    ];
                } elseif (property_exists($exception, 'getCode') && $exception->getCode()) {
                    $additionalContext['sql_state'] = $exception->getCode();
                }
            } elseif (method_exists($exception, 'getSqlState')) {
                $additionalContext['sql_state'] = $exception->getSqlState();
            }

            // Get request information if available
            $request      = app('request');
            $finalContext = array_merge([
                'url'        => $request->fullUrl() ?? 'N/A',
                'method'     => $request->method() ?? 'N/A',
                'ip_address' => $request->ip() ?? 'N/A',
                'user_agent' => $request->userAgent() ?? 'N/A',
                'user_id'    => auth()->id() ?? null,
                'session_id' => session()->getId() ?? null,
                'timestamp'  => now()->toISOString(),
            ], $context, $additionalContext);

            // Create the error log record
            return \App\Models\Sys\ErrorLog::create([
                'level'           => $level,
                'message'         => $exception->getMessage(),
                'exception_class' => get_class($exception),
                'file'            => $exception->getFile(),
                'line'            => $exception->getLine(),
                'trace'           => collect($exception->getTrace())->map(function ($trace) {
                    return [
                        'file'     => $trace['file'] ?? 'unknown',
                        'line'     => $trace['line'] ?? 'unknown',
                        'function' => $trace['function'] ?? 'unknown',
                        'class'    => $trace['class'] ?? null,
                        'type'     => $trace['type'] ?? null,
                    ];
                })->toArray(),
                'context'         => $finalContext,
                'url'             => $finalContext['url'] ?? $request->fullUrl(),
                'method'          => $finalContext['method'] ?? $request->method(),
                'ip_address'      => $finalContext['ip_address'] ?? $request->ip(),
                'user_agent'      => $finalContext['user_agent'] ?? $request->userAgent(),
                'user_id'         => $finalContext['user_id'] ?? auth()->id(),
            ]);
        } catch (\Throwable $logError) {
            // If logging fails, at least log to standard Laravel logs
            \Log::error('Failed to log error to sys_error_log: ' . $logError->getMessage());
            return null;
        }
    }
}


use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Session;

if (! function_exists('setActiveRole')) {
    /**
     * Set the active role for the authenticated user
     */
    function setActiveRole($roleName)
    {
        session(['active_role' => $roleName]);
    }
}

if (! function_exists('getActiveRole')) {
    /**
     * Get the active role for the authenticated user
     */
    function getActiveRole()
    {
        return session('active_role', auth()->user()->getRoleNames()->first());
    }
}

if (! function_exists('getAllUserRoles')) {
    /**
     * Get all roles assigned to the authenticated user
     */
    function getAllUserRoles()
    {
        return auth()->user()->getRoleNames();
    }
}

if (! function_exists('formatTanggalIndo')) {
    /**
     * Format tanggal ke bahasa Indonesia
     */
    function formatTanggalIndo($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY HH:mm:ss');
    }
}

if (! function_exists('formatTanggalWaktuIndo')) {
    /**
     * Format tanggal dan waktu ke bahasa Indonesia
     */
    function formatTanggalWaktuIndo($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm');
    }
}

if (! function_exists('generateQrCodeImage')) {
    /**
     * Generate QR code image and save to file
     *
     * @param string $text Text to encode in QR code
     * @param string $filename Filename to save the QR code image
     * @param string|null $directory Directory to save the image (default: storage/app/qrcodes)
     * @return string Path to the saved QR code image
     */
    function generateQrCodeImage($text, $filename, $directory = null)
    {
        if (! $directory) {
            $directory = storage_path('app/qrcodes');
        }

        if (! file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = $directory . '/' . $filename;

        // Generate QR code using BaconQrCode (as used in TestController)
        $renderer  = new GDLibRenderer(200);
        $writer    = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($text);

        // Save the PNG data to file
        file_put_contents($filePath, $qrCodePng);

        return $filePath;
    }
}

if (! function_exists('generateQrCodeBase64')) {
    /**
     * Generate QR code as base64 encoded image
     *
     * @param string $text Text to encode in QR code
     * @return string Base64 encoded QR code image
     */
    function generateQrCodeBase64($text)
    {
        // Generate QR code using BaconQrCode (as used in TestController)
        $renderer  = new GDLibRenderer(200);
        $writer    = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($text);

        // Encode to base64
        $base64Image = base64_encode($qrCodeSvg);

        return $base64Image;
    }
}

if (! function_exists('sysDataTableSearchValue')) {
    /**
     * Standardize extracting search string from DataTables request
     *
     * @param mixed $searchValue
     * @return string
     */
    function sysDataTableSearchValue($searchValue)
    {
        if (is_array($searchValue)) {
            return $searchValue['value'] ?? '';
        }
        return (string) $searchValue;
    }
}

if (! function_exists('sysParseDateRange')) {
    /**
     * Standardize parsing "to" separated date strings
     *
     * @param string $rangeString
     * @return array [start, end]
     */
    function sysParseDateRange($rangeString)
    {
        if (! $rangeString) return [null, null];

        $dates = explode(' to ', $rangeString);
        if (count($dates) === 2) {
            return [trim($dates[0]), trim($dates[1])];
        }

        return [trim($dates[0]), trim($dates[0])]; // Single date
    }
}

if (! function_exists('sysGenerateRefNumber')) {
    /**
     * Standardize sequential reference number generation
     *
     * @param string $prefix Prefix like "REG-YYYY-"
     * @param string $modelClass Model to check
     * @param string $column Column name
     * @param int $padding Length of counter
     * @return string
     */
    function sysGenerateRefNumber($prefix, $modelClass, $column, $padding = 4)
    {
        $last = $modelClass::where($column, 'like', $prefix . '%')
            ->orderBy($column, 'desc')
            ->first();

        if (! $last) {
            $number = 1;
        } else {
            // Extract number from the end of the string
            // Assuming the number is the last part after the prefix
            $lastRef = $last->{$column};
            $lastNumberStr = substr($lastRef, strlen($prefix));
            $number = (int) $lastNumberStr + 1;
        }

        return $prefix . str_pad($number, $padding, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('downloadStorageFile')) {
    /**
     * Helper terpusat untuk mengunduh file dari Laravel Storage.
     *
     * Menggantikan pola manual response()->download() yang tersebar di controller.
     * Semua validasi, MIME type detection, dan sanitasi nama file ditangani di sini.
     *
     * @param  string|null  $storagePath     Path relatif dari hasil ->store() (misal: "public/pemutu/ed-attachments/file.pdf")
     * @param  string|null  $downloadFilename Nama file yang diterima user saat download. Jika null, gunakan nama asli.
     * @param  bool         $logActivity     Apakah perlu log aktivitas download (default: false)
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     *
     * @example
     *   // Dengan custom filename:
     *   return downloadStorageFile($model->attachment, 'Laporan_KPI_2025.pdf');
     *
     *   // Tanpa custom filename (pakai nama asli dari storage):
     *   return downloadStorageFile($model->ed_attachment);
     */
    function downloadStorageFile(?string $storagePath, ?string $downloadFilename = null, bool $logActivity = false)
    {
        // 1. Validasi path tidak kosong
        if (empty($storagePath)) {
            abort(404, 'File lampiran tidak ditemukan.');
        }

        // 2. Normalisasi path — cegah directory traversal
        $storagePath = normalizePath($storagePath);

        // 3. Cek keberadaan file di storage
        if (! \Illuminate\Support\Facades\Storage::exists($storagePath)) {
            abort(404, 'File tidak ditemukan di server. Mungkin sudah dihapus.');
        }

        // 4. Tentukan nama file output
        if (empty($downloadFilename)) {
            // Gunakan nama asli dari path storage
            $downloadFilename = basename($storagePath);
        } else {
            // Sanitasi: hapus karakter berbahaya, pertahankan ekstensi
            $originalExt    = pathinfo($storagePath, PATHINFO_EXTENSION);
            $safeBasename   = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($downloadFilename, PATHINFO_FILENAME));
            $providedExt    = pathinfo($downloadFilename, PATHINFO_EXTENSION);
            $finalExt       = $providedExt ?: $originalExt;
            $downloadFilename = $safeBasename . ($finalExt ? '.' . $finalExt : '');
        }

        // 5. Log aktivitas (opsional)
        if ($logActivity) {
            logActivity('system', 'Download file: ' . $downloadFilename);
        }

        // 6. Stream file ke browser
        return \Illuminate\Support\Facades\Storage::download($storagePath, $downloadFilename);
    }
}

