<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordForgotRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Response\ApiResponse;
use App\Mail\EmailMail;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    private PasswordResetService $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function sendResetLinkEmail(PasswordForgotRequest $request): ApiResponse
    {
        $resetPasswordUrl = $this->passwordResetService->sendResetLinkEmail($request->getDTO()->user);

        Mail::to($request->getDTO()->user->email)
            ->send(new EmailMail('Reset Password Notification', 'emails.passwordResetLinkEmail', [
                'user' => $request->getDTO()->user,
                'dataUrl' => $resetPasswordUrl
            ]));

        return new ApiResponse('Password reset link sent');
    }

    public function reset(PasswordResetRequest $request)
    {
        $dto = $request->getDTO();

        $status = Password::reset(
            [
                'email' => $dto->email,
                'password' => $dto->password,
                'password_confirmation' => $dto->password_confirmation,
                'token' => $dto->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(__($status))
            : response()->json(['email' => __($status)]);
    }
}
