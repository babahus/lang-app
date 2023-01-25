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
            'data' => 'required',
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)],
        ];
    }

    public function getDTO(): CreateExerciseDTO
    {
        return new CreateExerciseDTO(
            $this->input('data'),
            $this->input('type')
        );
    }
}
