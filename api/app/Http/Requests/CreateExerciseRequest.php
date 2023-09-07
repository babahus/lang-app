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
                ExercisesTypes::AUDIT            => ['required', 'file', 'mimes:mp3,wav,flac,ogg', 'max:12048'],
                ExercisesTypes::PICTURE_EXERCISE => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
                default                          => 'nullable'
            },
            'additional_data' => match (ExercisesTypes::inEnum($this->input('type'))) {
                ExercisesTypes::AUDIT               => ['required', 'string'],
                ExercisesTypes::SENTENCE,
                ExercisesTypes::PICTURE_EXERCISE,   => ['required', 'nullable','json'],
                default => 'nullable',
            },
            'type'                                  => ['required', 'string', new Enum(ExercisesTypes::class)],
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
 $this->input('additional_data') ?? null,

        );
    }
}
