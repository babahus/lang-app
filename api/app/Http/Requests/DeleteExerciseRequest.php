<?php

namespace App\Http\Requests;

use App\DataTransfers\DeleteExerciseDTO;
use App\Enums\ExercisesTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class DeleteExerciseRequest extends FormRequest
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
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)],
            'data' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::AUDIT => ['nullable'],
                ExercisesTypes::DICTIONARY => ['required', function ($attribute, $value, $fail) {
                    // Try to decode the value as JSON
                    $decodedValue = json_decode($value, true);
                    // Check if the value was successfully decoded and contains the expected keys
                    if ($decodedValue === null || !isset($decodedValue['word']) || !isset($decodedValue['translate'])) {
                        $fail("The $attribute field must be a valid JSON object with 'word' and 'translate' keys.");
                    }
                }],
                ExercisesTypes::PAIR_EXERCISE => ['required', function ($attribute, $value, $fail) {
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
                }],
            },
        ];
    }

    public function getDTO(): DeleteExerciseDTO
    {
        return new DeleteExerciseDTO(
            $this->input('type'),
            $this->input('data') ?? null
        );
    }
}
