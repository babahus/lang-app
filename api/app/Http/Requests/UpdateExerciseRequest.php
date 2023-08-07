<?php

namespace App\Http\Requests;

use App\DataTransfers\UpdateExerciseDTO;
use App\Enums\ExercisesTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class UpdateExerciseRequest extends BaseRequest
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
            'data' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::AUDIT            => ['required', 'file', 'mimes:mp3,wav,flac', 'max:12048'],
                ExercisesTypes::COMPILE_PHRASE   => ['required'],
                ExercisesTypes::PICTURE_EXERCISE => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
                ExercisesTypes::DICTIONARY, ExercisesTypes::PAIR_EXERCISE => ['required', function ($attribute, $value, $fail) {
                    $decodedValue = json_decode($value, true);

                    if (!is_array($decodedValue)) {
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
                ExercisesTypes::SENTENCE => ['required', 'string'],
            },
            'additional_data' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::AUDIT               => ['required', 'string'],
                ExercisesTypes::SENTENCE         => ['required', 'nullable','json'],
                ExercisesTypes::PICTURE_EXERCISE =>  ['required', function ($attribute, $arrOptions, $fail) {
                    $decodedOptions = json_decode($arrOptions, true);

                    if (!is_array($decodedOptions) || count($decodedOptions) < 2) {
                        $fail("The $attribute field must be a valid JSON array with at least two elements.");
                    } else {
                        $hasCorrectAnswer = false;

                        foreach ($decodedOptions as $option) {

                            if (!is_array($option) || empty($option['text']) || empty($option['is_correct'])) {
                                $fail("The $attribute is not being passed correctly to update the record");
                            } else {
                                if ($option['is_correct'] === 'true') {
                                    if ($hasCorrectAnswer) {
                                        $fail("Only one element in the $attribute array can have 'is_correct' set to true.");
                                    }
                                    $hasCorrectAnswer = true;
                                } elseif ($option['is_correct'] !== 'false') {
                                    $fail("The 'is_correct' value in each element of the $attribute array should be either 'true' or 'false'.");
                                }
                            }
                        }

                        if (!$hasCorrectAnswer) {
                            $fail("At least one element in the $attribute array must have 'is_correct' set to true.");
                        }
                    }
                }],
                default => 'nullable',
            },
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)]
        ];
    }

    public function getDTO(): UpdateExerciseDTO
    {
        return new UpdateExerciseDTO(
            match (ExercisesTypes::inEnum($this->input('type'))){
                ExercisesTypes::AUDIT,ExercisesTypes::PICTURE_EXERCISE  => $this->file('data'),
                default               => $this->input('data'),
            },
            $this->input('type'),
            $this->input('additional_data') ?? null,
        );
    }

    //match (ExercisesTypes::inEnum($this->input('type'))){
    //    ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrase', 'id')],
    //    ExercisesTypes::DICTIONARY     => ['required', 'numeric', Rule::exists('contracts_drafts', 'id')]
    //}
}
