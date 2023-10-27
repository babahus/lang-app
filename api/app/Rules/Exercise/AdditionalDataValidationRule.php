<?php

namespace App\Rules\Exercise;

use App\Enums\ExercisesTypes;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdditionalDataValidationRule implements Rule
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

        return match (ExercisesTypes::inEnum($type)){
            ExercisesTypes::AUDIT            => $this->audit_validation($value),
            ExercisesTypes::SENTENCE         => $this->sentence_validation($value),
            ExercisesTypes::PICTURE_EXERCISE => $this->picture_exercise_validation($value),

            default => 'nullable',
        };
    }

    public function audit_validation($value)
    {
        $validator = Validator::make(['value' => $value], [
            'value' => 'string|max:255',
        ]);

        return !$validator->fails();
    }

    public function sentence_validation($value)
    {
        $decodedValue = json_decode($value, true);

        if (!is_array($decodedValue)) {
            return false;
        }

        foreach ($decodedValue as $sentence) {
            if (!is_string($sentence) || mb_strlen($sentence, 'utf-8') > 255) {
                return false;
            }
        }

        return true;
    }


    public function picture_exercise_validation($value)
    {
        $decodedOptions = json_decode($value, true);

        if (!is_array($decodedOptions) || count($decodedOptions) < 2) {
            return false;
        }

        $hasCorrectAnswer = false;

        $validationRules = [];

        foreach ($decodedOptions as $index => $option) {
            $validationRules["options.$index.text"] = 'string|max:255';

            if (!is_array($option) || !isset($option['text']) || !isset($option['is_correct'])) {
                return false;
            }

            if (empty($option['text']) || !isset($option['is_correct'])) {
                return false;
            }

            $isCorrect = filter_var($option['is_correct'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            if ($isCorrect === null) {
                return false;
            }

            if ($isCorrect) {
                if ($hasCorrectAnswer) {
                    return false;
                }
                $hasCorrectAnswer = true;
            }
        }

        if (!$hasCorrectAnswer) {
            return false;
        }

        $validator = Validator::make(['options' => $decodedOptions], $validationRules);

        return !$validator->fails();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid additional data for the selected exercise type.';
    }
}
