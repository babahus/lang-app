<?php

namespace App\Rules\Exercise;

use App\Enums\ExercisesTypes;
use App\Models\Audit;
use App\Models\CompilePhrase;
use App\Models\Dictionary;
use App\Models\PairExercise;
use App\Models\PictureExercise;
use App\Models\Sentence;
use Illuminate\Contracts\Validation\Rule;

class MoveUserExerciseIdRule implements Rule
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
        $exerciseType = request()->input('exercise_type');

        return match (ExercisesTypes::inEnum($exerciseType)) {
            ExercisesTypes::COMPILE_PHRASE => $this->move_user_validation($value, new CompilePhrase()),
            ExercisesTypes::DICTIONARY => $this->move_user_validation($value, new Dictionary()),
            ExercisesTypes::AUDIT => $this->move_user_validation($value, new Audit()),
            ExercisesTypes::PAIR_EXERCISE => $this->move_user_validation($value,  new PairExercise()),
            ExercisesTypes::PICTURE_EXERCISE => $this->move_user_validation($value, new PictureExercise()),
            ExercisesTypes::SENTENCE => $this->move_user_validation($value, new Sentence()),

            default => false,
        };
    }

    public function move_user_validation($value, $objModel)
    {
        return $objModel->where('id', intval($value))->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The id check rule did not work for this type of exercise.';
    }
}
