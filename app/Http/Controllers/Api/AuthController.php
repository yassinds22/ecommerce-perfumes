<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Authentication
 *
 * API endpoints for user registration, login, and profile management.
 */
class AuthController extends BaseApiController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * User Registration.
     * 
     * **Use Case**: Allows new users to join the platform. The registration flow automatically handles profile creation and triggers welcome logic.
     * 
     * Create a new user account and receive a profile resource.
     * 
     * @unauthenticated
     * @bodyParam name string required The full name of the user. Example: John Doe
     * @bodyParam email string required The unique email address. Example: john@example.com
     * @bodyParam password string required The secure password (min 8 chars). Example: password123
     * @bodyParam password_confirmation string required Must match the password. Example: password123
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return $this->success(new UserResource($user), 'User registered successfully', 201);
    }

    /**
     * User Login.
     * 
     * **Use Case**: Primary entry point for authenticated interactions. Issues a persistent Bearer Token for SPAs and Mobile clients.
     * 
     * Authenticate credentials and receive a Bearer Token.
     * 
     * @unauthenticated
     * @bodyParam email string required The user's email. Example: john@example.com
     * @bodyParam password string required The user's password. Example: password123
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());
        return $this->success([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 'Login successful');
    }

    /**
     * Get authenticated user.
     * 
     * Retrieve the profile details of the currently logged-in user.
     * 
     * @authenticated
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success(new UserResource($request->user()));
    }

    /**
     * User Logout.
     * 
     * Revoke all API tokens for the authenticated user.
     * 
     * @authenticated
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return $this->success(null, 'Logged out successfully');
    }
}
