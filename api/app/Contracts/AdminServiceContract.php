<?php

namespace App\Contracts;

use App\DataTransfers\AdminStoreUserDTO;
use App\DataTransfers\AdminUpdateUserDTO;

interface AdminServiceContract
{
    public function index();
    public function store(AdminStoreUserDTO $adminStoreUserDTO);
    public function show(int $id);
    public function update(AdminUpdateUserDTO $adminUpdateUserDTO, $id);
    public function destroy(int $id);
    public function findUserById(int $id);
}
