<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\LoginDTO;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends BaseRequest
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
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'role'     => [
                'required',
                'string',
                'in:User,Teacher,Admin,Root',
                'exists:roles,name',
                function ($attribute, $value, $fail) {
                    $roleId = Role::where('name', $value)->value('id');
                    $user = User::where('email', $this->input('email'))->first();
                    $roleUser = User::whereHas('roles', function ($query) use ($roleId, $user) {
                        $query->where('role_id', $roleId)
                            ->where('user_id', $user->id);
                    })->exists();

                    if(!$roleUser){
                        $fail('Enter your current account role');
                    };
                }
            ],
        ];
    }

    public function getDTO(): LoginDTO
    {
        return new LoginDTO(
            $this->input('email'),
            $this->input('password'),
            $this->input('role')
        );
    }
}
