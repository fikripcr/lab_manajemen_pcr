<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WebAuthService
{
    /**
     * Authenticate a user with email and password for web login
     */
    public function webLogin(array $credentials, bool $remember = false): bool
    {
        if (Auth::attempt($credentials, $remember)) {
            return true;
        }

        return false;
    }

    /**
     * Rate limited web login with proper error handling
     */
    public function rateLimitedWebLogin(array $credentials, bool $remember = false, string $ip = null): void
    {
        $email = $credentials['email'];

        // Create a throttle key based on email and IP
        $throttleKey = Str::transliterate(Str::lower($email) . '|' . ($ip ?: request()->ip()));

        // Check if too many attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => ['Too many login attempts. Please try again in ' . $seconds . ' seconds.'],
            ]);
        }

        // Attempt authentication
        if (!Auth::attempt($credentials, $remember)) {
            // Increment the rate limiter
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Clear successful login attempts
        RateLimiter::clear($throttleKey);
    }
}