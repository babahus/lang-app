<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class PasswordResetDTO implements DTO
{
    public string $token;
    public string $email;
    public string $password;
    public string $password_confirmation;

    public function __construct(
        $token,
        $email,
        $password,
        $password_confirmation,
    )
    {
        $this->token = $token;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
    }
}
