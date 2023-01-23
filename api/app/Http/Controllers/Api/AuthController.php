<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\AuthService;
use App\Http\Response\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): ApiResponse
    {
        $user = $this->authService->login($request->getDTO());
        if (!$user){
            return new ApiResponse('Invalid Credentials', 401, false);
        }

        return new ApiResponse(new UserResource($user));
    }

    public function register(RegisterRequest $request): ApiResponse
    {
        $user = $this->authService->register($request->getDTO());

        return new ApiResponse(new UserResource($user));
    }
}
