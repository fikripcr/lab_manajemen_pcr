<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Models\Sys\ErrorLog;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        // Laravel otomatis tidak report exception di sini
        AuthenticationException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        // tambahkan lainnya jika perlu
    ];

    public function register(): void
    {
        // Hook untuk semua exception yang lolos $dontReport
        $this->reportable(function (Throwable $e) {
            // Extract additional context data from exception if it's a database exception
            $additionalContext = [];

            if ($e instanceof \PDOException || $e instanceof \Illuminate\Database\QueryException) {
                if (isset($e->errorInfo) && is_array($e->errorInfo)) {
                    $additionalContext = [
                        'error_info' => [
                            'SQLSTATE' => $e->errorInfo[0] ?? null,
                            'Driver Code' => $e->errorInfo[1] ?? null,
                            'Driver Message' => $e->errorInfo[2] ?? null,
                        ],
                    ];
                } elseif (property_exists($e, 'getCode') && $e->getCode()) {
                    $additionalContext['sql_state'] = $e->getCode();
                }
            } elseif (method_exists($e, 'getSqlState')) {
                $additionalContext['sql_state'] = $e->getSqlState();
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
            ], $additionalContext);

            // Create the error log record
            try {
                ErrorLog::create([
                    'level' => 'error',
                    'message' => $e->getMessage(),
                    'exception_class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => collect($e->getTrace())->map(function ($trace) {
                        return [
                            'file' => $trace['file'] ?? 'unknown',
                            'line' => $trace['line'] ?? 'unknown',
                            'function' => $trace['function'] ?? 'unknown',
                            'class' => $trace['class'] ?? null,
                            'type' => $trace['type'] ?? null,
                        ];
                    })->toArray(),
                    'context' => $finalContext,
                    'url' => $finalContext['url'],
                    'method' => $finalContext['method'],
                    'ip_address' => $finalContext['ip_address'],
                    'user_agent' => $finalContext['user_agent'],
                    'user_id' => $finalContext['user_id'],
                ]);
            } catch (\Throwable $logError) {
                // If we can't log the error to the ErrorLog model, at least write it to standard Laravel log
                \Log::error('Failed to log error to sys_error_log: ' . $logError->getMessage());
            }
        });
    }

    // Optional: jika ingin custom render (misal: JSON di API)
    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }
}
