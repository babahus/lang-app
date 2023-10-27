<?php

namespace App\Rules\Exercise\Solving;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make(['value' => $value], [
            'value' => 'string|max:255',
        ]);

        return !$validator->fails();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be a mandatory string and must not exceed 255 characters.';
    }
}
