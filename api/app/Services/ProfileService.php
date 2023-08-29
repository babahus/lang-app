<?php

namespace App\Services;

use App\Contracts\ProfileServiceContract;
use App\DataTransfers\EmailChangeDTO;
use App\DataTransfers\PasswordResetDTO;
use App\DataTransfers\Profile\PasswordChangeDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

final class ProfileService implements ProfileServiceContract
{
    public function createUrlVerification(User $user): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(24),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );
    }

    public function sendResetLinkEmail(User $user): string
    {
        $token = Password::createToken($user);

        return url('/reset-password' . '?token=' . $token) . '?email=' . urlencode($user->email);
    }

    public function changeEmail(User $user, EmailChangeDTO $emailChangeDTO): bool
    {
        $user->email = $emailChangeDTO->email;
        $user->email_verified_at = null;

        return $user->save();
    }

    public function changePassword(User $user, PasswordChangeDTO $passwordChangeDTO): bool
    {
         $user->forceFill([
            'password' => Hash::make($passwordChangeDTO->new_password),
        ])->save();

        return true;
    }

    public function resetPassword(PasswordResetDTO $passwordResetDTO): string
    {
        $status = Password::reset(
            [
                'email' => $passwordResetDTO->email,
                'password' => $passwordResetDTO->password,
                'password_confirmation' => $passwordResetDTO->password_confirmation,
                'token' => $passwordResetDTO->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status;
    }
}