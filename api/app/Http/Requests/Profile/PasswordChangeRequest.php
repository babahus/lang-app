<?php

namespace App\Http\Requests\Profile;

use App\DataTransfers\Profile\PasswordChangeDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PasswordChangeRequest extends FormRequest
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
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user()->password)) {
                        $fail('Current password is incorrect');
                    }
                },
            ],
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]+$/',
            ],
            'password_confirmation' => 'required|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'new_password.regex' => 'Your password must contain one capital letter, 1 number and be longer than 8 characters.'
        ];
    }

    public function getDTO(): PasswordChangeDTO
    {
        return new PasswordChangeDTO(
            $this->input('current_password'),
            $this->input('new_password'),
            $this->input('password_confirmation'),
        );
    }
}
