<?php

namespace App\Rules\Solving;

use Illuminate\Contracts\Validation\Rule;

class PairExerciseRule implements Rule
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

        if ($decodedValue === null || !is_array($decodedValue)) {
            return false;
        }

        foreach ($decodedValue as $item) {
            if (!is_array($item) || !isset($item['word']) || !isset($item['translation'])) {
                return false;
            }
            if (empty($item['word']) || empty($item['translation'])) {
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
        return 'The :attribute field must be a valid JSON array containing objects with "word" and "translation" keys, and non-empty values.';
    }
}
