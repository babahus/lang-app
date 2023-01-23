<?php
namespace App\Services;

use App\Models\User;
use App\DataTransfers\LoginDTO;
use App\DataTransfers\RegisterDTO;
use App\Contracts\AuthContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthContract
{

    public function register(RegisterDTO $registerDTO): User
    {
        $encryptPassword = Hash::make($registerDTO->password);
        $user = User::create([
            'name'     => $registerDTO->name,
            'email'    => $registerDTO->email,
            'password' => $encryptPassword
        ]);
        $user->roles()->attach(1);
        return $user;
    }

    public function login(LoginDTO $loginDTO): \Illuminate\Contracts\Auth\Authenticatable|bool
    {
        if (! auth()->attempt(([
            'email'    => $loginDTO->email,
            'password' => $loginDTO->password
        ]))) {
            return false;
        }

        return Auth::user();
    }
}
