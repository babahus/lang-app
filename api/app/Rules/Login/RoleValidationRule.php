<?php

namespace App\Rules\Login;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class RoleValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $roleId = Role::where('name', $value)->value('id');
        $user = User::where('email', request()->input('email'))->first();

        if (!$user) {
            return false;
        }

        return User::whereHas('roles', function ($query) use ($roleId, $user) {
            $query->where('role_id', $roleId)
                ->where('user_id', $user->id);
        })->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Enter your current account role';
    }
}
