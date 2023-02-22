<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\CreateExerciseDTO;
use App\Enums\ExercisesTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateExerciseRequest extends BaseRequest
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
            'data' => match (ExercisesTypes::inEnum($this->input('type'))){
                    ExercisesTypes::COMPILE_PHRASE => ['required', 'string'],
                    ExercisesTypes::DICTIONARY     => ['required', 'json'],
                    ExercisesTypes::AUDIT          => ['required', 'file', 'mimes:mp3,wav,flac', 'max:12048'],
                    default                        => 'nullable'
            },
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)],
        ];
    }

    public function getDTO(): CreateExerciseDTO
    {
        return new CreateExerciseDTO(
            match (ExercisesTypes::inEnum($this->input('type'))){
                ExercisesTypes::AUDIT => $this->file('data'),
                default => $this->input('data'),
            },
            $this->input('type')
        );
    }
}
