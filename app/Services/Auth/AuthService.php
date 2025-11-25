<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
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

    /**
     * Authenticate a user and create a Sanctum token for API access
     */
    public function createApiToken(string $email, string $password, string $deviceName = 'api-token'): ?array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a Sanctum token for the user
        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Login user and create Sanctum token in a single method
     */
    public function loginAndCreateToken(array $credentials, string $deviceName = 'api-token'): ?array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a Sanctum token for the user
        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Revoke a specific Sanctum token
     */
    public function revokeToken(string $tokenId, int $userId): bool
    {
        $token = PersonalAccessToken::findToken($tokenId);

        if ($token && $token->tokenable_id === $userId) {
            $token->delete();
            return true;
        }

        return false;
    }

    /**
     * Revoke all tokens for a specific user
     */
    public function revokeAllUserTokens(int $userId): bool
    {
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception("User with ID {$userId} not found.");
        }

        $user->tokens()->delete();

        return true;
    }

    /**
     * Authenticate user for API (validate credentials without creating token)
     */
    public function authenticateApiUser(array $credentials): ?User
    {
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Generate a Sanctum token for an already authenticated user
     */
    public function generateTokenForUser(User $user, string $deviceName = 'api-token'): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }
}