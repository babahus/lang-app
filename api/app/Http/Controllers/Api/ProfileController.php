<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\PasswordChangeRequest;
use App\Http\Response\ApiResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function changePassword(PasswordChangeRequest $request): ApiResponse
    {
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($request->getDTO()->new_password),
        ])->save();

        return new ApiResponse('Password changed successfully');
    }
}
