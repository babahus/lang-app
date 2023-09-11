<?php

namespace App\Rules\Solving;

use Illuminate\Contracts\Validation\Rule;

class CompilePhraseRule implements Rule
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
        $data = json_decode($value);

        if (is_array($data)){
            return false;
        }

        return is_string($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be a required string.';
    }
}