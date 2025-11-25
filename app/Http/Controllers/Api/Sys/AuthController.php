<?php

namespace App\Http\Controllers\Api\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\ApiAuthService;
use App\Services\Auth\TokenManagementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected ApiAuthService $apiAuthService;
    protected TokenManagementService $tokenManagementService;

    public function __construct(ApiAuthService $apiAuthService, TokenManagementService $tokenManagementService)
    {
        $this->apiAuthService = $apiAuthService;
        $this->tokenManagementService = $tokenManagementService;
    }

    /**
     * Authenticate user and create API token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $deviceName = $request->input('device_name', $request->userAgent());

            $result = $this->apiAuthService->loginAndCreateToken($credentials, $deviceName);

            if (!$result) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Log the login activity
            logActivity('auth', 'API token generated for user: ' . $result['user']->name, $result['user']);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $result['user'],
                    'token' => $result['token'],
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during authentication',
            ], 500);
        }
    }

    /**
     * Revoke the user's API token
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user(); // Get authenticated user from Sanctum token

            // Revoke the current token
            $request->user()->currentAccessToken()->delete();

            // Log the logout activity
            logActivity('auth', 'API token revoked for user: ' . $user->name, $user);

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
            ], 500);
        }
    }

    /**
     * Get authenticated user details
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred retrieving user information',
            ], 500);
        }
    }
}