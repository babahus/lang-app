<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/');
    }

    public function sendVerificationNotification(Request $request): ApiResponse
    {

        dd(auth()->user());
        dd($user);
        if ($user->hasVerifiedEmail()) {
            return new ApiResponse('Email is already verified.');
        }

        $user->sendEmailVerificationNotification();

        return new ApiResponse('Verification link sent');
    }
}
