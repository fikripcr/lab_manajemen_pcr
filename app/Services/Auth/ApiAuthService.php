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