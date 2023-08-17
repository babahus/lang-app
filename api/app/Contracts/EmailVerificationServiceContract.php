<?php

namespace App\Contracts;

use App\Models\User;

interface EmailVerificationServiceContract
{
    public function createUrlVerification(User $user): string;
}
