<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailChangeRequest;
use App\Http\Requests\PasswordForgotRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\Profile\PasswordChangeRequest;
use App\Http\Response\ApiResponse;
use App\Mail\EmailMail;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function getProfileInfo()
    {
        $profileInfo = $this->profileService->getProfileInfo();

        if (!$profileInfo){

            return new ApiResponse('Something went wrong', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse($profileInfo);
    }

    public function verify(EmailVerificationRequest $request): ApiResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return new ApiResponse('Email already verified');
        }

        $user->markEmailAsVerified();

        return new ApiResponse('Email has been verified');
    }

    public function sendVerificationNotification(): ApiResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return new ApiResponse('Email is already verified.');
        }

        $this->sendVerificationEmail($user);

        return new ApiResponse('Verification link sent');
    }

    private function sendVerificationEmail(User $user): void
    {
        $verificationUrl = $this->profileService->createUrlVerification($user);

        Mail::to($user->email)
            ->send(new EmailMail('Verify Your Email', 'emails.verificationEmail', [
                'user' => $user,
                'dataUrl' => $verificationUrl
            ]));
    }

    public function changeEmail(EmailChangeRequest $request): ApiResponse
    {
        $changed = $this->profileService->changeEmail($request->user(), $request->getDTO());

        if (!$changed) {
            return new ApiResponse('Invalid Provider', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse('Email changed successfully', Response::HTTP_OK);
    }

    public function changePassword(PasswordChangeRequest $request): ApiResponse
    {
        $changed = $this->profileService->changePassword($request->user(), $request->getDTO());

        if (!$changed) {
            return new ApiResponse('Invalid Provider', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse('Password changed successfully', Response::HTTP_OK);
    }

    public function sendResetLinkEmail(PasswordForgotRequest $request): ApiResponse
    {
        $resetPasswordUrl = $this->profileService->sendResetLinkEmail($request->getDTO()->user);

        Mail::to($request->getDTO()->user->email)
            ->send(new EmailMail('Reset Password Notification', 'emails.passwordResetLinkEmail', [
                'user' => $request->getDTO()->user,
                'dataUrl' => $resetPasswordUrl
            ]));

        return new ApiResponse('Password reset link sent');
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $status = $this->profileService->resetPassword($request->getDTO());

        if ($status === Password::PASSWORD_RESET) {
            return new ApiResponse('Password reset successfully', Response::HTTP_OK);
        } else {
            return new ApiResponse('Password reset failed', Response::HTTP_BAD_REQUEST, false);
        }
    }

    public function getCachedInfo(Request $request): ApiResponse
    {
        $user = auth()->user();
        $userRole = $this->profileService->getUserRoleFromCache($user);

        return new ApiResponse(['userRole' => $userRole->name, 'userId' => $user->id]);
    }
}
