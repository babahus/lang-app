<?php

namespace App\Services;

use App\Models\{
    Role,
    User,
};
use App\DataTransfers\{
    AdminStoreUserDTO,
    AdminUpdateUserDTO
};
use App\Contracts\AdminServiceContract;

final class AdminService implements AdminServiceContract
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function index(): \Illuminate\Database\Eloquent\Collection|array
    {
        return User::with('roles')->get();
    }

    /**
     * @param AdminStoreUserDTO $adminStoreUserDTO
     * @return \Illuminate\Database\Eloquent\Model|User
     */
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

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function show(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return User::with('roles')->find($id);
    }

    /**
     * @param AdminUpdateUserDTO $adminUpdateUserDTO
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User|null
     */
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

    /**
     * @param int $id
     * @return bool
     */
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

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User|null
     */
    public function findUserById(int $id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User|null
    {
        return User::whereId($id)->with('roles')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getRoles(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Role::all();
    }
}
