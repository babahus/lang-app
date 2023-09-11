<?php

namespace App\Rules\Exercise\Solving;

use Illuminate\Contracts\Validation\Rule;

class DictionaryRule implements Rule
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
        $decodedValue = json_decode($value, true);

        return $decodedValue !== null && isset($decodedValue['word']) && isset($decodedValue['translation']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be a valid JSON object with word and translation keys.';
    }
}
