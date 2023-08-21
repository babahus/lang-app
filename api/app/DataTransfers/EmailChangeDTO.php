<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class EmailChangeDTO implements DTO
{
    public string $email;

    public function __construct(
        string $email,
    )
    {
        $this->email = $email;
    }
}
