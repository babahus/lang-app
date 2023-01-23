<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class LoginDTO implements DTO
{
    public readonly string $email;
    public readonly string $password;

    public function __construct(
        $email,
        $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
