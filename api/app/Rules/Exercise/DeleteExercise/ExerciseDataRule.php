<?php

namespace App\Rules\Exercise\DeleteExercise;

use App\Enums\ExercisesTypes;
use Illuminate\Contracts\Validation\Rule;
use function Symfony\Component\Translation\t;

class ExerciseDataRule implements Rule
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
        $type = request()->input('type');

        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::AUDIT,
            ExercisesTypes::PICTURE_EXERCISE, ExercisesTypes::PAIR_EXERCISE,
            ExercisesTypes::SENTENCE => true,
            ExercisesTypes::DICTIONARY => $this->dictionary_validation($value),

            default => false,
        };
    }

    public function dictionary_validation($value)
    {
        $decodedValue = json_decode($value, true);

        foreach ($decodedValue as $item)
        {
            if ($item === null || !isset($item['word']) || !isset($item['translation'])) {
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
        return 'The validation error message.';
    }
}
