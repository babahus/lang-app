<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class LoginDTO implements DTO
{
    public readonly string $email;
    public readonly string $password;
    public readonly string $role;

    public function __construct(
        $email,
        $password,
        $role
    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
}
