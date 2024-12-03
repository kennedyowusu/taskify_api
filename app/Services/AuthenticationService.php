<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Http\Resources\v1\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        (!$user || !Hash::check($data['password'], $user->password)) ?? response()->json(['message' => 'Unauthorized'], 401);

        $userToken = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success(['user' => new UserResource($user), 'access_token' => $userToken], 'User logged in successfully');
    }
}
