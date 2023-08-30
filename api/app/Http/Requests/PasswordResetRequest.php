<?php

namespace App\Http\Requests;

use App\DataTransfers\PasswordResetDTO;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]+$/'
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Your password must contain one capital letter, 1 number and be longer than 8 characters.'
        ];
    }

    public function getDTO(): PasswordResetDTO
    {
        return new PasswordResetDTO(
            $this->input('token'),
            $this->input('email'),
            $this->input('password'),
            $this->input('password_confirmation')
        );
    }
}
