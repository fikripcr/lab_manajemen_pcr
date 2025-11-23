<?php

if (!function_exists('apiResponse')) {
    /**
     * Create standardized API response
     */
    function apiResponse($data = null, $message = null, $code = 200, $status = 'success')
    {
        $response = [
            'status' => $status,
            'code' => $code
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('apiSuccess')) {
    /**
     * Create standardized success API response
     */
    function apiSuccess($data = null, $message = 'Operation successful', $code = 200)
    {
        return apiResponse($data, $message, $code, 'success');
    }
}

if (!function_exists('apiError')) {
    /**
     * Create standardized error API response
     */
    function apiError($message = 'An error occurred', $code = 400, $data = null)
    {
        return apiResponse($data, $message, $code, 'error');
    }
}

if (!function_exists('apiPaginated')) {
    /**
     * Create standardized paginated API response
     */
    function apiPaginated($paginator, $additionalData = [])
    {
        $paginationData = [
            'data' => $paginator->items(),
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'path' => $paginator->path(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ]
        ];

        if (!empty($additionalData)) {
            $paginationData = array_merge($paginationData, $additionalData);
        }

        return apiSuccess($paginationData);
    }
}

if (!function_exists('encryptId')) {
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

if (!function_exists('decryptId')) {
    /**
     * Decrypt a Hashid to get the original ID
     *
     * @param string $hash
     * @param bool $throwException Whether to throw exception on failure
     * @return int|null
     */
    function decryptId($hash, $throwException = true)
    {
        if (!$hash) {
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

if (!function_exists('logActivity')) {
    function logActivity($logName, $description, $subject = null, $properties = [])
    {
        // Get the current user
        $causer = auth()->user();

        // Get IP address and user agent from the request
        $request = request();
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
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => $causer?->id,
            'user_name' => $causer?->name,
        ]);

        $activity->withProperties($properties)->log($description);
    }
}

if (!function_exists('normalizePath')) {
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

if (!function_exists('formatBytes')) {
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

if (!function_exists('logError')) {
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
            if (!$error instanceof \Throwable) {
                $exception = new \Exception($error);
            }

            // Extract additional context data from exception if it's a database exception
            $additionalContext = [];

            if ($exception instanceof \PDOException || $exception instanceof \Illuminate\Database\QueryException) {
                if (isset($exception->errorInfo) && is_array($exception->errorInfo)) {
                    $additionalContext = [
                        'error_info' => [
                            'SQLSTATE' => $exception->errorInfo[0] ?? null,
                            'Driver Code' => $exception->errorInfo[1] ?? null,
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
            $request = app('request');
            $finalContext = array_merge([
                'url' => $request->fullUrl() ?? 'N/A',
                'method' => $request->method() ?? 'N/A',
                'ip_address' => $request->ip() ?? 'N/A',
                'user_agent' => $request->userAgent() ?? 'N/A',
                'user_id' => auth()->id() ?? null,
                'session_id' => session()->getId() ?? null,
                'timestamp' => now()->toISOString(),
            ], $context, $additionalContext);

            // Create the error log record
            return \App\Models\Sys\ErrorLog::create([
                'level' => $level,
                'message' => $exception->getMessage(),
                'exception_class' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => collect($exception->getTrace())->map(function ($trace) {
                    return [
                        'file' => $trace['file'] ?? 'unknown',
                        'line' => $trace['line'] ?? 'unknown',
                        'function' => $trace['function'] ?? 'unknown',
                        'class' => $trace['class'] ?? null,
                        'type' => $trace['type'] ?? null,
                    ];
                })->toArray(),
                'context' => $finalContext,
                'url' => $finalContext['url'] ?? $request->fullUrl(),
                'method' => $finalContext['method'] ?? $request->method(),
                'ip_address' => $finalContext['ip_address'] ?? $request->ip(),
                'user_agent' => $finalContext['user_agent'] ?? $request->userAgent(),
                'user_id' => $finalContext['user_id'] ?? auth()->id(),
            ]);
        } catch (\Throwable $logError) {
            // If logging fails, at least log to standard Laravel logs
            \Log::error('Failed to log error to sys_error_log: ' . $logError->getMessage());
            return null;
        }
    }
}

if (!function_exists('validation_messages_id')) {
    function validation_messages_id()
    {
        return [
            'accepted'        => ':attribute harus diterima.',
            'active_url'      => ':attribute bukan URL yang valid.',
            'after'           => ':attribute harus berisi tanggal setelah :date.',
            'after_or_equal'  => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
            'alpha'           => ':attribute hanya boleh berisi huruf.',
            'alpha_dash'      => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'alpha_num'       => ':attribute hanya boleh berisi huruf dan angka.',
            'array'           => ':attribute harus berupa array.',
            'before'          => ':attribute harus berisi tanggal sebelum :date.',
            'before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
            'between'         => [
                'numeric' => ':attribute harus di antara :min dan :max.',
                'file'    => ':attribute harus berukuran antara :min dan :max kilobita.',
                'string'  => ':attribute harus terdiri dari :min sampai :max karakter.',
                'array'   => ':attribute harus memiliki :min sampai :max item.',
            ],
            'boolean'         => ':attribute harus berupa true atau false.',
            'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
            'date'            => ':attribute bukan tanggal yang valid.',
            'date_equals'     => ':attribute harus berisi tanggal yang sama dengan :date.',
            'date_format'     => ':attribute tidak cocok dengan format :format.',
            'different'       => ':attribute dan :other harus berbeda.',
            'digits'          => ':attribute harus terdiri dari :digits digit.',
            'digits_between'  => ':attribute harus terdiri dari :min sampai :max digit.',
            'dimensions'      => ':attribute memiliki dimensi gambar yang tidak valid.',
            'distinct'        => ':attribute memiliki nilai yang duplikat.',
            'email'           => ':attribute harus berupa alamat email yang valid.',
            'ends_with'       => ':attribute harus diakhiri dengan salah satu dari: :values.',
            'exists'          => ':attribute yang dipilih tidak valid.',
            'file'            => ':attribute harus berupa file.',
            'filled'          => ':attribute harus memiliki nilai.',
            'gt'              => [
                'numeric' => ':attribute harus lebih besar dari :value.',
                'file'    => ':attribute harus berukuran lebih besar dari :value kilobita.',
                'string'  => ':attribute harus lebih dari :value karakter.',
                'array'   => ':attribute harus memiliki lebih dari :value item.',
            ],
            'gte'             => [
                'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
                'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
                'string'  => ':attribute harus minimal :value karakter.',
                'array'   => ':attribute harus memiliki :value item atau lebih.',
            ],
            'image'           => ':attribute harus berupa gambar.',
            'in'              => ':attribute yang dipilih tidak valid.',
            'in_array'        => ':attribute tidak ada di dalam :other.',
            'integer'         => ':attribute harus berupa bilangan bulat.',
            'ip'              => ':attribute harus berupa alamat IP yang valid.',
            'ipv4'            => ':attribute harus berupa alamat IPv4 yang valid.',
            'ipv6'            => ':attribute harus berupa alamat IPv6 yang valid.',
            'json'            => ':attribute harus berupa string JSON yang valid.',
            'lt'              => [
                'numeric' => ':attribute harus kurang dari :value.',
                'file'    => ':attribute harus berukuran kurang dari :value kilobita.',
                'string'  => ':attribute harus kurang dari :value karakter.',
                'array'   => ':attribute harus memiliki kurang dari :value item.',
            ],
            'lte'             => [
                'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
                'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
                'string'  => ':attribute maksimal :value karakter.',
                'array'   => ':attribute harus memiliki paling banyak :value item.',
            ],
            'max'             => [
                'numeric' => ':attribute tidak boleh lebih dari :max.',
                'file'    => ':attribute tidak boleh lebih dari :max kilobita.',
                'string'  => ':attribute tidak boleh lebih dari :max karakter.',
                'array'   => ':attribute tidak boleh lebih dari :max item.',
            ],
            'mimes'           => ':attribute harus berupa file berjenis: :values.',
            'mimetypes'       => ':attribute harus berupa file berjenis: :values.',
            'min'             => [
                'numeric' => ':attribute minimal :min.',
                'file'    => ':attribute minimal :min kilobita.',
                'string'  => ':attribute minimal :min karakter.',
                'array'   => ':attribute minimal :min item.',
            ],
            'not_in'          => ':attribute yang dipilih tidak valid.',
            'not_regex'       => 'Format :attribute tidak valid.',
            'numeric'         => ':attribute harus berupa angka.',
            'password'        => 'Kata sandi salah.',
            'present'         => ':attribute harus ada.',
            'regex'           => 'Format :attribute tidak valid.',
            'required'        => ':attribute wajib diisi.',
            'required_if'     => ':attribute wajib diisi ketika :other bernilai :value.',
            'required_unless' => ':attribute wajib diisi kecuali :other ada di :values.',
            'required_with'   => ':attribute wajib diisi bila :values ada.',
            'required_with_all' => ':attribute wajib diisi bila :values ada.',
            'required_without' => ':attribute wajib diisi bila :values tidak ada.',
            'required_without_all' => ':attribute wajib diisi bila semua :values tidak ada.',
            'same'            => ':attribute dan :other harus sama.',
            'size'            => [
                'numeric' => ':attribute harus berukuran :size.',
                'file'    => ':attribute harus berukuran :size kilobita.',
                'string'  => ':attribute harus terdiri dari :size karakter.',
                'array'   => ':attribute harus mengandung :size item.',
            ],
            'starts_with'     => ':attribute harus diawali salah satu dari: :values.',
            'string'          => ':attribute harus berupa string.',
            'timezone'        => ':attribute harus berupa zona waktu yang valid.',
            'unique'          => ':attribute sudah digunakan.',
            'uploaded'        => ':attribute gagal diunggah.',
            'url'             => 'Format :attribute tidak valid.',
            'uuid'            => ':attribute harus berupa UUID yang valid.',

            // Authentication specific messages
            'failed'          => 'Kredensial yang Anda masukkan tidak valid.',
            'throttle'        => 'Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.',

            // Custom attributes (optional)
            'attributes' => [],
        ];
    }
}

use Illuminate\Support\Facades\Session;

if (!function_exists('setActiveRole')) {
    /**
     * Set the active role for the authenticated user
     */
    function setActiveRole($roleName)
    {
        session(['active_role' => $roleName]);
    }
}

if (!function_exists('getActiveRole')) {
    /**
     * Get the active role for the authenticated user
     */
    function getActiveRole()
    {
        return session('active_role', auth()->user()->getRoleNames()->first());
    }
}

if (!function_exists('getAllUserRoles')) {
    /**
     * Get all roles assigned to the authenticated user
     */
    function getAllUserRoles()
    {
        return auth()->user()->getRoleNames();
    }
}

if (!function_exists('formatTanggalIndo')) {
    /**
     * Format tanggal ke bahasa Indonesia
     */
    function formatTanggalIndo($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY HH:mm:ss');
    }
}

if (!function_exists('formatTanggalWaktuIndo')) {
    /**
     * Format tanggal dan waktu ke bahasa Indonesia
     */
    function formatTanggalWaktuIndo($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm');
    }
}
