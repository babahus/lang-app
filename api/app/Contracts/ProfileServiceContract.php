<?php

namespace App\Contracts;

use App\DataTransfers\EmailChangeDTO;
use App\DataTransfers\PasswordResetDTO;
use App\DataTransfers\Profile\PasswordChangeDTO;
use App\Models\User;

interface ProfileServiceContract
{
    public function sendResetLinkEmail(User $user): string;
    public function createUrlVerification(User $user): string;
    public function resetPassword(PasswordResetDTO $passwordResetDTO): string;
    public function changePassword(User $user, PasswordChangeDTO $passwordChangeDTO): bool;
    public function changeEmail(User $user, EmailChangeDTO $emailChangeDTO): bool;
}
