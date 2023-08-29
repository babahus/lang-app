<?php

namespace App\Contracts;

use App\Models\User;
use App\DataTransfers\RegisterDTO;
use App\DataTransfers\LoginDTO;

interface AuthContract
{
    public function register(RegisterDTO $registerDTO) : array;
    public function login(LoginDTO $loginDTO) : array;
    public function findOrCreateUser(User $user, string $provider);
}
