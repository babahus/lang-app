<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use App\Models\User;

class PasswordForgotDTO implements DTO
{
    public string $email;
    public User $user;

    public function __construct(
        string $email,
        User $user
    )
    {
        $this->email = $email;
        $this->user = $user;
    }
}
