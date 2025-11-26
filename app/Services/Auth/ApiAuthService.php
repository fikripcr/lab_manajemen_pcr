<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class ApiAuthService
{
    /**
     * Authenticate a user and create a Sanctum token for API access
     */
    public function createApiToken(string $email, string $password, string $deviceName = 'api-token'): ?array
    {
        $authService = new AuthService();
        $user = $authService->verifyCredentials(['email' => $email, 'password' => $password]);

        if (!$user) {
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
        $authService = new AuthService();
        $user = $authService->verifyCredentials($credentials);

        if (!$user) {
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
     * Authenticate user for API (validate credentials without creating token)
     */
    public function authenticateApiUser(array $credentials): ?User
    {
        $authService = new AuthService();
        return $authService->verifyCredentials($credentials);
    }

    /**
     * Generate a Sanctum token for an already authenticated user
     */
    public function generateTokenForUser(User $user, string $deviceName = 'api-token'): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }
}