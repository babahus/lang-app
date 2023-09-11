<?php

namespace App\Rules\Exercise\Solving;

use Illuminate\Contracts\Validation\Rule;

class SentenceRule implements Rule
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
        $decodedValue = json_decode($value);

        return json_last_error() === JSON_ERROR_NONE && is_array($decodedValue);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be a valid JSON array.';
    }
}
