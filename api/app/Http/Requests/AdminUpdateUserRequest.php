<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\AdminUpdateUserDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateUserRequest extends BaseRequest
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
            'name'                  => 'sometimes|max:255',
            'email'                 => ['sometimes', 'email',
                                        Rule::unique('users','email')
                                        ->ignore($this->segment(3))
                                       ],
            'role_id'               => 'sometimes|exists:roles,id'
        ];
    }

    public function getDTO(): AdminUpdateUserDTO
    {
        return new AdminUpdateUserDTO(
            $this->input('name'),
            $this->input('email'),
            $this->input('role_id'),
        );
    }
}
