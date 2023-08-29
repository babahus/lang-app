<?php

namespace App\Http\Requests;

use App\DataTransfers\RegisterDTO;

final class RegisterRequest extends BaseRequest
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
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]+$/'
            ],
            'password_confirmation' => 'required|same:password',
            'role'                  => 'required|in:User,Teacher|exists:roles,name'
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Your password must contain one capital letter and must be longer than 8 characters.'
        ];
    }

    public function getDTO(): RegisterDTO
    {
        return new RegisterDTO(
            $this->input('name'),
            $this->input('email'),
            $this->input('password'),
            $this->input('password_confirmation'),
            $this->input('role')
        );
    }
}
