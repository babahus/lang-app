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

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedValue)) {
            return false;
        }

        foreach ($decodedValue as $item) {
            if (!is_string($item) || mb_strlen($item, 'utf-8') > 255) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be a mandatory string and each value must not exceed 255 characters.';
    }
}
