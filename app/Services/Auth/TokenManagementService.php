<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\Sys\PersonalAccessToken;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class TokenManagementService
{
    /**
     * Revoke a specific Sanctum token
     */
    public function revokeToken(string $tokenId, int $userId): bool
    {
        $token = SanctumPersonalAccessToken::findToken($tokenId);

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
     * Get all tokens for a specific user
     */
    public function getUserTokens(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception("User with ID {$userId} not found.");
        }

        return $user->tokens;
    }

    /**
     * Revoke tokens by name for a specific user
     */
    public function revokeTokensByName(int $userId, string $name): int
    {
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception("User with ID {$userId} not found.");
        }

        return $user->tokens()
            ->where('name', $name)
            ->delete();
    }
}