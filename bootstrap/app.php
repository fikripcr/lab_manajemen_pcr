<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 'auth' => \App\Http\Middleware\Authenticate::class,
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'impersonate.protect' => \Lab404\Impersonate\Middleware\ProtectFromImpersonation::class,
            'check.expired'      => \App\Http\Middleware\CheckAccountExpiration::class,
            // 'validatePasswordResetToken' => \App\Http\Middleware\ValidatePasswordResetToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Report all exceptions to our custom ErrorLog model
        $exceptions->report(function (\Throwable $e) {
            // Only don't log specific exceptions that are routine and not errors
            if ($e instanceof \Illuminate\Validation\ValidationException  ||
                $e instanceof \Illuminate\Auth\AuthenticationException) {
                return; // Don't log these as they are routine validation/auth issues, not errors
            }

            // Create error log record
            try {
                // Extract additional context data from exception if it's a database exception
                $additionalContext = [];

                if ($e instanceof \PDOException  || $e instanceof \Illuminate\Database\QueryException) {
                    if (isset($e->errorInfo) && is_array($e->errorInfo)) {
                        $additionalContext = [
                            'error_info' => [
                                'SQLSTATE'       => $e->errorInfo[0] ?? null,
                                'Driver Code'    => $e->errorInfo[1] ?? null,
                                'Driver Message' => $e->errorInfo[2] ?? null,
                            ],
                        ];
                    } elseif (property_exists($e, 'getCode') && $e->getCode()) {
                        $additionalContext['sql_state'] = $e->getCode();
                    }
                } elseif (method_exists($e, 'getSqlState')) {
                    $additionalContext['sql_state'] = $e->getSqlState();
                }

                $request   = app('request');
                $errorData = [
                    'level'           => 'error',
                    'message'         => $e->getMessage(),
                    'exception_class' => get_class($e),
                    'file'            => $e->getFile(),
                    'line'            => $e->getLine(),
                    'trace'           => collect($e->getTrace())->map(function ($trace) {
                        return [
                            'file'     => $trace['file'] ?? 'unknown',
                            'line'     => $trace['line'] ?? 'unknown',
                            'function' => $trace['function'] ?? 'unknown',
                            'class'    => $trace['class'] ?? null,
                            'type'     => $trace['type'] ?? null,
                        ];
                    })->toArray(),
                    'context'         => array_merge([
                        'url'        => $request->fullUrl(),
                        'method'     => $request->method(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'user_id'    => auth()->id(),
                        'session_id' => session()->getId(),
                        'timestamp'  => now()->toISOString(),
                    ], $additionalContext),
                ];

                \App\Models\Sys\ErrorLog::create($errorData);
            } catch (\Throwable $logError) {
                // If we can't log the error to the ErrorLog model, at least write it to standard Laravel log
                \Log::error('Failed to log error to sys_error_log: ' . $logError->getMessage());
            }
        });
    })->create();
