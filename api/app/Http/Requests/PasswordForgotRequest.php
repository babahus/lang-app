<?php

namespace App\Http\Requests;

use App\DataTransfers\PasswordForgotDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasswordForgotRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function getUserByEmail()
    {
        return User::where('email', $this->input('email'))->first();
    }

    public function getDTO(): PasswordForgotDTO
    {
        $user = $this->getUserByEmail();

        return new PasswordForgotDTO(
            $this->input('email'),
            $user
        );
    }
}
