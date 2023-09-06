<?php

namespace App\Rules\Solving;

use App\Enums\ExercisesResourcesTypes;
use App\Models\Exercise;
use Illuminate\Contracts\Validation\Rule;

class ExerciseIdExistsRule implements Rule
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
        $exercise = Exercise::find($value);

        if (!$exercise) {
            return false;
        }

        $type = ExercisesResourcesTypes::inEnum(strtoupper(request('type')))->value;

        if ($exercise->exercise_type !== $type || $exercise->exercise_id !== intval(request('id'))) {
            return false;
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
        return 'The specified exercise_id does not exist or does not match the exercise type and id.';
    }
}
