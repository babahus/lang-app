<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class AdminStoreUserDTO implements DTO
{
    public readonly string $name;
    public readonly string $email;
    public readonly string $password;
    public readonly int $role_id;

    public function __construct(
        $name,
        $email,
        $password,
        $role_id
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = $role_id;
    }
}
