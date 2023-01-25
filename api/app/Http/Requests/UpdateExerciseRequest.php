<?php

namespace App\Http\Requests;

use App\DataTransfers\UpdateExerciseDTO;
use App\Enums\ExercisesTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateExerciseRequest extends BaseRequest
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
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)]
        ];
    }

    public function getDTO(): UpdateExerciseDTO
    {
        return new UpdateExerciseDTO(
            $this->input('data'),
            $this->input('type')
        );
    }

    //match (ExercisesTypes::inEnum($this->input('type'))){
    //    ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrase', 'id')],
    //    ExercisesTypes::DICTIONARY     => ['required', 'numeric', Rule::exists('contracts_drafts', 'id')]
    //}
}
