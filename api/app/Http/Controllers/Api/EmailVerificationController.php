<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Mail\EmailMail;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    private EmailVerificationService $verificationService;

    public function __construct(EmailVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
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
        $verificationUrl = $this->verificationService->createUrlVerification($user);

        Mail::to($user->email)
            ->send(new EmailMail('Verify Your Email', 'emails.verificationEmail', [
                'user' => $user,
                'dataUrl' => $verificationUrl
            ]));
    }
}
