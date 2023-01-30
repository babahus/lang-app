<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\AdminStoreUserDTO;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'role_id'               => 'required|exists:roles,id'
        ];
    }

    public function getDTO(): AdminStoreUserDTO
    {
        return new AdminStoreUserDTO(
            $this->input('name'),
            $this->input('email'),
            $this->input('password'),
            $this->input('role_id'),
        );
    }
}
