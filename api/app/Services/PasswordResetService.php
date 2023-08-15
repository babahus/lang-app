<?php

namespace App\Services;

use App\Contracts\PasswordResetServiceContract;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

final class PasswordResetService implements PasswordResetServiceContract
{
    public function sendResetLinkEmail(User $user): string
    {
        $token = Password::createToken($user);

        return url('/reset-password' . '?token=' . $token) . '?email=' . urlencode($user->email);
    }
}
