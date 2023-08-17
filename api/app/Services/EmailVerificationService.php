<?php

namespace App\Services;

use App\Contracts\EmailVerificationServiceContract;
use App\Models\User;
use Illuminate\Support\Facades\URL;

final class EmailVerificationService implements EmailVerificationServiceContract
{
    public function createUrlVerification(User $user): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(24),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );
    }
}
