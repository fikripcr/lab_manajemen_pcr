<?php
namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\ApiAuthService;
use App\Services\Auth\TokenManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected ApiAuthService $apiAuthService,
        protected TokenManagementService $tokenManagementService
    ) {}

    /**
     * Authenticate user and create API token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $deviceName  = $request->input('device_name', $request->userAgent());

        $result = $this->apiAuthService->loginAndCreateToken($credentials, $deviceName);

        if (! $result) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Log the login activity
        logActivity('auth', 'API token generated for user: ' . $result['user']->name, $result['user']);

        return jsonSuccess('Login successful', null, [
            'user'  => $result['user'],
            'token' => $result['token'],
        ]);
    }

    /**
     * Revoke the user's API token
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user(); // Get authenticated user from Sanctum token

        // Revoke the current token
        $request->user()->currentAccessToken()->delete();

        // Log the logout activity
        logActivity('auth', 'API token revoked for user: ' . $user->name, $user);

        return jsonSuccess('Successfully logged out');
    }

    /**
     * Get authenticated user details
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return jsonSuccess('User information retrieved', null, $user);
    }
}
