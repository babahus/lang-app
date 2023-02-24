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
                default => 'nullable'
            },
            'data' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::AUDIT => ['required'],
                ExercisesTypes::DICTIONARY => ['required', function ($attribute, $value, $fail) {
                    // Try to decode the value as JSON
                    $decodedValue = json_decode($value, true);
                    // Check if the value was successfully decoded and contains the expected keys
                    if ($decodedValue === null || !isset($decodedValue['word']) || !isset($decodedValue['translate'])) {
                        $fail("The $attribute field must be a valid JSON object with 'word' and 'translate' keys.");
                    }
                }],
                default => 'nullable',

            },
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)]
        ];
    }

    public function getDTO(): SolvingExerciseDTO
    {
        return new SolvingExerciseDTO(
            $this->input('id'),
            $this->input('type'),
            $this->input('data')
        );
    }
}
