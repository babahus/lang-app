<?php

namespace App\Contracts;

use App\Models\User;
use App\DataTransfers\RegisterDTO;
use App\DataTransfers\LoginDTO;

interface AuthContract
{
    public function register(RegisterDTO $registerDTO) : User;
    public function login(LoginDTO $loginDTO) : \Illuminate\Contracts\Auth\Authenticatable|bool;

}
