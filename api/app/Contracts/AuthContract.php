<?php

namespace App\Contracts;

use App\Models\User;
use App\DataTransfers\RegisterDTO;
use App\DataTransfers\LoginDTO;

interface AuthContract
{
    public function register(RegisterDTO $registerDTO) : array;
    public function login(LoginDTO $loginDTO) : bool|array;
    public function findOrCreateUser(User $user, string $provider);
    public function createToken(User $user);
}
