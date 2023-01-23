<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
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
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
    }

    public function getDTO(): LoginDTO
    {
        return new LoginDTO(
            $this->input('email'),
            $this->input('password')
        );
    }
}
