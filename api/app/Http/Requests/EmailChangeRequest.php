<?php

namespace App\Http\Requests;

use App\DataTransfers\EmailChangeDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmailChangeRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,' . Auth::id()
        ];
    }

    public function getDTO(): EmailChangeDTO
    {
        return new EmailChangeDTO(
            $this->input('email'),
        );
    }
}
