<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\LoginUserRequest;
use App\Http\Requests\v1\RegisterUserRequest;
use App\Http\Resources\v1\UserResource;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{

    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function registerUser(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        // Delegate registration logic to the service
        $newUser = $this->authService->register($validatedData);

        $userToken = $newUser->createToken('authToken')->plainTextToken;

        // return response(['user' => new UserResource($newUser), 'access_token' => $userToken], 201);
        return ApiResponse::success(['user' => new UserResource($newUser), 'access_token' => $userToken], 'User registered successfully', 201);
    }

    public function loginUser(LoginUserRequest $request)
    {
        $validatedData = $request->validated();

        $key = 'login-attempts:' . Str::lower($validatedData['email']) . '|' . $request->ip();

        // Check rate limit
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response([
                'message' => 'Too many login attempts. Please try again in ' . RateLimiter::availableIn($key) . ' seconds.',
            ], 429);
        }

         // Use AuthenticationService to perform login logic
        $response = $this->authService->login($validatedData);

        if ($response->getStatusCode() !== 200) {
            // Increment the rate limit counter on failed attempt
            RateLimiter::hit($key, 60);
            return $response;
        }
        // Clear the rate limit counter on successful login
        RateLimiter::clear($key);

        return $response;

    }

    public function logoutUser(Request $request)
    {
        $request->user()->tokens()->delete();

        return ApiResponse::success([], 'User logged out');
    }
}
