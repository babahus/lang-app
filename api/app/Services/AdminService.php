<?php

namespace App\Services;

use App\Contracts\AdminServiceContract;
use App\DataTransfers\AdminStoreUserDTO;
use App\DataTransfers\AdminUpdateUserDTO;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminService implements AdminServiceContract
{
    public function index(): \Illuminate\Database\Eloquent\Collection|array
    {
        return User::with('roles')->get();
    }

    public function store(AdminStoreUserDTO $adminStoreUserDTO): \Illuminate\Database\Eloquent\Model|User
    {
        $user = User::create([
            'name'  => $adminStoreUserDTO->name,
            'email' => $adminStoreUserDTO->email,
            'password' => \Hash::make($adminStoreUserDTO->password)
        ]);
        $user->roles()->attach($adminStoreUserDTO->role_id);
        return $user;
    }

    public function show(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return User::with('roles')->find($id);
    }
    public function update(AdminUpdateUserDTO $adminUpdateUserDTO, $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User|null
    {
        $user = $this->findUserById($id);

        $user->fill([
            'name' => $adminUpdateUserDTO->name,
            'email' => $adminUpdateUserDTO->email,
            'email_verified_at' => null
        ]);
        $user->save();
        return $user;

    }

    public function destroy(int $id): bool
    {
        $user = $this->findUserById($id);
        if (!$user){
            return false;
        }
        $user->delete();
        $user->roles()->detach($id);
        return true;
    }

    public function findUserById(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User|null
    {
        return User::whereId($id)->with('roles')->first();
    }

    public function getRoles(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Role::all();
    }
}
