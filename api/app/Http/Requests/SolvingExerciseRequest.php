<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\SolvingExerciseDTO;
use App\Enums\ExercisesTypes;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class SolvingExerciseRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrases', 'id')],
                ExercisesTypes::DICTIONARY => ['required', 'numeric', Rule::exists('dictionaries', 'id')],
                ExercisesTypes::AUDIT => ['required', 'numeric', Rule::exists('audits', 'id')],
                ExercisesTypes::PAIR_EXERCISE => ['required', 'numeric', Rule::exists('pair_exercises', 'id')],
                ExercisesTypes::PICTURE_EXERCISE => ['required', 'numeric', Rule::exists('picture_exercises', 'id')],
                ExercisesTypes::SENTENCE => ['required', 'numeric', Rule::exists('sentence', 'id')],
                default => 'nullable'
            },
            'data' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::AUDIT, ExercisesTypes::PICTURE_EXERCISE => ['required','string'],
                ExercisesTypes::DICTIONARY => ['required', function ($attribute, $value, $fail) {
                    // Try to decode the value as JSON
                    $decodedValue = json_decode($value, true);
                    // Check if the value was successfully decoded and contains the expected keys
                    if ($decodedValue === null || !isset($decodedValue['word']) || !isset($decodedValue['translate'])) {
                        $fail("The $attribute field must be a valid JSON object with 'word' and 'translate' keys.");
                    }
                }],
                ExercisesTypes::SENTENCE => ['required', function ($attribute, $value, $fail) {
                    $decodedValue = json_decode($value);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail("The $attribute field must be a valid JSON array.");
                    }
                }],
                ExercisesTypes::PAIR_EXERCISE => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $decodedValue = json_decode($value, true);

                        if ($decodedValue === null || !is_array($decodedValue)) {
                            $fail("The $attribute field must be a valid JSON array.");
                        } else {
                            foreach ($decodedValue as $item) {
                                if (!is_array($item) || !isset($item['word']) || !isset($item['translation'])) {
                                    $fail("The $attribute field must be a valid JSON array containing objects with 'word' and 'translation' keys.");
                                    break;
                                }
                                if (empty($item['word']) || empty($item['translation'])) {
                                    $fail("The 'word' and 'translation' values in $attribute must not be empty.");
                                    break;
                                }
                            }
                        }
                    }
                ],

                default => 'nullable',

            },
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)],
            'exercise_id' => ['required', 'numeric','exists:accounts_exercises,id']
        ];
    }

    public function getDTO(): SolvingExerciseDTO
    {
        return new SolvingExerciseDTO(
            $this->input('id'),
            $this->input('type'),
            $this->input('data'),
            $this->input('exercise_id'),
        );
    }
}
