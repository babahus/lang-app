<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\{LoginRequest, LoginSocialiteRequest, RegisterRequest};
use Illuminate\Http\Response;
use App\Enums\ProvidersTypes;
use App\Services\AuthService;
use App\Http\Response\ApiResponse;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\UserResponseResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    /**
     * @param LoginRequest $request
     * @return ApiResponse
     */
    public function login(LoginRequest $request): ApiResponse
    {
        $userData = $this->authService->login($request->getDTO());

        if (!$userData){

            return new ApiResponse('Invalid Credentials', 401, false);
        }

        return new ApiResponse(new UserResponseResource($userData));
    }

    /**
     * @param RegisterRequest $request
     * @return ApiResponse
     */
    public function register(RegisterRequest $request): ApiResponse
    {
        $user = $this->authService->register($request->getDTO());

        return new ApiResponse(new UserResponseResource($user));
    }

    /**
     * @param string $provider
     * @return ApiResponse
     */
    public function getProviderLink(string $provider)
    {
        if (!ProvidersTypes::tryFrom($provider)){

            return new ApiResponse('Invalid Provider', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse(Socialite::with($provider)->stateless()->redirect()->getTargetUrl());
    }

    /**
     * @param string $provider
     * @return ApiResponse
     */
    public function handleProviderCallback(LoginSocialiteRequest $loginSocialiteRequest, string $provider): ApiResponse
    {

        if (!ProvidersTypes::tryFrom($provider)){

            return new ApiResponse('Invalid Provider', Response::HTTP_BAD_REQUEST, false);
        }

        $user = Socialite::driver($provider)->stateless()->user();

        $authUser = $this->authService->findOrCreateUser($user, $provider, $loginSocialiteRequest->input('role'));

        if (!$authUser){

            return new ApiResponse('The user does not have the required role', ResponseAlias::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse(new UserResponseResource($authUser));
    }

    /**
     * @return ApiResponse
     */
    public function logout(): ApiResponse
    {
        Cache::forget("users_role_" . auth()->user()->id);
        Cache::forget("users_token_" . auth()->user()->id);

        auth()->user()->tokens()->delete();

        return new ApiResponse('Successfully logged out');
    }
}
