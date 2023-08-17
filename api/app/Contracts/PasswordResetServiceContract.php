<?php

namespace App\Contracts;

use App\Models\User;

interface PasswordResetServiceContract
{
    public function sendResetLinkEmail(User $user): string;
}
