<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class RegisterDTO implements DTO
{
    public readonly string $name;
    public readonly string $email;
    public readonly string $password;
    public readonly string $password_confirmation;
    public readonly string $role;

    public function __construct(
        $name,
        $email,
        $password,
        $password_confirmation,
        $role
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
        $this->role = $role;
    }
}
