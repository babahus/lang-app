<?php

namespace App\Http\Controllers\Api;

use App\Mail\EmailMail;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
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
    private ProfileService $profileService;

    /**
     * @param AuthService $authService
     * @param ProfileService $profileService
     */
    public function __construct(AuthService $authService, ProfileService $profileService){
        $this->authService = $authService;
        $this->profileService = $profileService;
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
        $verificationUrl = $this->profileService->createUrlVerification($user['user']);

        Mail::to($user['user']->email)
            ->send(new EmailMail('Verify Your Email', 'emails.verificationEmail', [
                'user' => $user['user'],
                'dataUrl' => $verificationUrl
            ]));

        return new ApiResponse(new UserResponseResource($user));
    }

    /**
     * @param string $provider
     * @return ApiResponse
     */
    public function getProviderLink(string $provider, LoginSocialiteRequest $request)
    {
        if (!ProvidersTypes::tryFrom($provider)){

            return new ApiResponse('Invalid Provider', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse(Socialite::with($provider)->with(['state' => json_encode([ 'role' => $request->input('role')])])->stateless()->redirect()->getTargetUrl());
    }

    /**
     * @param string $provider
     * @return ApiResponse
     */
    public function handleProviderCallback(string $provider, LoginSocialiteRequest $request): ApiResponse
    {
        if (!ProvidersTypes::tryFrom($provider)){
            return new ApiResponse('Invalid Provider', Response::HTTP_BAD_REQUEST, false);
        }

        $user = Socialite::driver($provider)->stateless()->user();

        $authUser = $this->authService->findOrCreateUser($user, $provider, $request->input('role'));

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
