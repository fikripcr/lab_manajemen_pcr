<?php

use App\Http\Middleware\CheckAccountExpiration;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

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
            'role'               => RoleMiddleware::class,
            'permission'         => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'check.expired'      => CheckAccountExpiration::class,
            // 'validatePasswordResetToken' => \App\Http\Middleware\ValidatePasswordResetToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Report all exceptions to our custom ErrorLog model
        $exceptions->report(function (\Throwable $e) {
            // Only don't log specific exceptions that are routine and not errors
            if ($e instanceof ValidationException ||
                $e instanceof AuthenticationException) {
                return; // Don't log ini karena ini error rutin user
            }

            // Simpan ke sys_error_log menggunakan helper tersentralisasi
            logError($e);
        });

        // Global response handler untuk request AJAX/JSON dan HTTP
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Handle specific survey exceptions
            if ($e->getMessage() === 'AUTH_REQUIRED') {
                return redirect()->route('login')->with('error', 'Anda harus login untuk mengisi survei ini.');
            }
            if ($e->getMessage() === 'ALREADY_FILLED') {
                return redirect()->route('dashboard')->with('error', 'Anda sudah mengisi survei ini.');
            }

            // 1. Tangani request AJAX/JSON
            if ($request->expectsJson()) {
                if ($e instanceof ValidationException) {
                    return null;
                }
                return jsonError('Terjadi kesalahan sistem: ' . $e->getMessage());
            }

            // 2. Tangani request HTTP Biasa
            if ($e instanceof ValidationException) {
                return null;
            }

            // Jika error terjadi pada saat submit data (bukan GET), redirect back dengan flash message
            if (!$request->isMethod('GET')) {
                return back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            }

            return null; // Fallback ke default handler (Error Page 500) untuk request GET
        });
    })->create();
