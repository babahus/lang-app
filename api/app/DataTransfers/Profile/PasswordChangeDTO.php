<?php

namespace App\DataTransfers\Profile;

use App\Contracts\DTO;

class PasswordChangeDTO implements DTO
{
    public string $current_password;
    public string $new_password;
    public string $password_confirmation;

    public function __construct(
        $current_password,
        $new_password,
        $password_confirmation,
    )
    {
        $this->current_password = $current_password;
        $this->new_password = $new_password;
        $this->password_confirmation = $password_confirmation;
    }
}
