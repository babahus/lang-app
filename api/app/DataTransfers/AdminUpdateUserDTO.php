<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class AdminUpdateUserDTO implements DTO
{
    public readonly string $name;
    public readonly string $email;
    public readonly string $password;
    public readonly int $role_id;

    public function __construct(
        $name,
        $email,
        $role_id
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->role_id = $role_id;
    }
}
