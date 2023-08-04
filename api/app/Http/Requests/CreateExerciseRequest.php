<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\CreateExerciseDTO;
use App\Enums\ExercisesTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class CreateExerciseRequest extends BaseRequest
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
            'data'       => match (ExercisesTypes::inEnum($this->input('type'))){
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::SENTENCE => ['required', 'string'],
                ExercisesTypes::DICTIONARY, ExercisesTypes::PAIR_EXERCISE => ['required', 'nullable', 'json'],
                ExercisesTypes::AUDIT            => ['required', 'file', 'mimes:mp3,wav,flac', 'max:12048'],
                ExercisesTypes::PICTURE_EXERCISE => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
                default                          => 'nullable'
            },
            'transcript' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::DICTIONARY,
                ExercisesTypes::PAIR_EXERCISE, ExercisesTypes::PICTURE_EXERCISE,
                ExercisesTypes::SENTENCE  => ['nullable'],
                ExercisesTypes::AUDIT     => ['required', 'string'],
            },
            'option_json' => match (ExercisesTypes::inEnum($this->input('type'))){
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::DICTIONARY,
                ExercisesTypes::PAIR_EXERCISE, ExercisesTypes::AUDIT,
                ExercisesTypes::SENTENCE => ['nullable'],
                ExercisesTypes::PICTURE_EXERCISE    => ['required', 'nullable', 'json'],
            },
            'correct_answers_json' => match (ExercisesTypes::inEnum($this->input('type'))){
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::DICTIONARY,
                ExercisesTypes::PAIR_EXERCISE, ExercisesTypes::AUDIT,
                ExercisesTypes::PICTURE_EXERCISE => ['nullable'],
                ExercisesTypes::SENTENCE    => ['required', 'nullable', 'json'],
            },
            'type'                                                          => ['required', 'string', new Enum(ExercisesTypes::class)],
        ];
    }

    public function getDTO(): CreateExerciseDTO
    {
        return new CreateExerciseDTO(
            match (ExercisesTypes::inEnum($this->input('type'))){
                ExercisesTypes::AUDIT,ExercisesTypes::PICTURE_EXERCISE  => $this->file('data'),
                default               => $this->input('data'),
            },
            $this->input('type'),
 $this->input('transcript') ?? null,
        $this->input('option_json') ?? null,
        $this->input('correct_answers_json') ?? null
        );
    }
}
