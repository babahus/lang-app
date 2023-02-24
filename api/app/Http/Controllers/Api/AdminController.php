<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Services\AdminService;
use App\Http\Requests\{
    AdminStoreUserRequest,
    AdminUpdateUserRequest,
};
use App\Http\Resources\{
    UserResource,
    RolesResource,
};
use App\Http\Response\ApiResponse;
use App\Http\Controllers\Controller;

final class AdminController extends Controller
{
    /**
     * @var AdminService
     */
    private AdminService $adminService;

    /**
     * @param AdminService $adminService
     */
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ApiResponse
     */
    public function index(): ApiResponse
    {
        $users = $this->adminService->index();

        return new ApiResponse(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreUserRequest $request
     * @return ApiResponse
     */
    public function store(AdminStoreUserRequest $request): ApiResponse
    {
        $createdUser = $this->adminService->store($request->getDTO());

        return new ApiResponse(UserResource::make($createdUser));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return ApiResponse
     */
    public function show(int $id): ApiResponse
    {
        $selectedUser = $this->adminService->show($id);

        if (!$selectedUser){

            return new ApiResponse('Invalid id', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse(UserResource::make($selectedUser));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateUserRequest $request
     * @param  int  $id
     * @return ApiResponse
     */
    public function update(AdminUpdateUserRequest $request, int $id): ApiResponse
    {
       $updatedUser = $this->adminService->update($request->getDTO(), $id);

       return new ApiResponse(UserResource::make($updatedUser));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(int $id): ApiResponse
    {
        $isDeleted = $this->adminService->destroy($id);

        if (!$isDeleted){

            return new ApiResponse('User not found', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse('User is successfully deleted');
    }

    /**
     * Return roles
     *
     * @return ApiResponse
     */
    public function getRoles(): ApiResponse
    {
        $roles = $this->adminService->getRoles();

        return new ApiResponse(RolesResource::collection($roles));
    }
}
