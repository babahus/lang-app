<?php

namespace App\Rules\Exercise;

use App\Enums\ExercisesTypes;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DataValidationRule implements Rule
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
            ExercisesTypes::AUDIT            => $this->audit_validation($value),
            ExercisesTypes::DICTIONARY,
            ExercisesTypes::PAIR_EXERCISE    => $this->pair_exercise_validation($value),
            ExercisesTypes::COMPILE_PHRASE   => $this->compile_phrase_validation($value),
            ExercisesTypes::PICTURE_EXERCISE => $this->picture_exercise_validation($value),
            ExercisesTypes::SENTENCE         => $this->sentence_validation($value),

            default => false,
        };
    }

    public function audit_validation($value)
    {
        return is_file($value) && in_array($value->getClientOriginalExtension(), ['mp3', 'wav', 'flac', 'ogg']) && ($value->getSize() / (1024 * 1024)) <= 100;
    }

    public function compile_phrase_validation($value)
    {
        $validator = Validator::make(['value' => $value], [
            'value' => ['string', 'max:255', 'regex:/\b\w+\b.*\b\w+\b/'],
        ]);

        return !$validator->fails();
    }
    public function picture_exercise_validation($value)
    {
        return is_file($value) && in_array($value->getClientOriginalExtension(), ['jpg','jpeg','png']) && ($value->getSize() / (1024 * 1024)) <= 100;
    }

    public function pair_exercise_validation($value)
    {
        if (!is_array(json_decode($value))) {
            return false;
        }

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

        $validator = Validator::make($decodedValue, [
            '*.word' => 'string|min:1|max:255',
            '*.translation' => 'string|min:1|max:255',
        ]);

        return !$validator->fails();
    }


    public function sentence_validation($value)
    {
        $validator = Validator::make(['value' => $value], [
            'value' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return false;
        }

        $words = json_decode(request()->input('additional_data', []));

        foreach ($words as $word) {
            if (!str_contains($value, $word)) {
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
        return 'Invalid data for the selected exercise type.';
    }
}
