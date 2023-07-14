<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\MoveUserExerciseDTO;
use App\Enums\ExercisesTypes;
use App\Http\Response\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class MoveUserExerciseRequest extends BaseRequest
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
            'id' => match (ExercisesTypes::inEnum($this->input('type'))){
                        ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrases', 'id')],
                        ExercisesTypes::DICTIONARY => ['required', 'numeric', Rule::exists('dictionaries', 'id')],
                        ExercisesTypes::AUDIT => ['required', 'numeric', Rule::exists('audits', 'id')],
                        default => 'nullable'
                    },
            'type' => ['required', 'string', new Enum(ExercisesTypes::class)]
        ];
    }

    public function getDTO(): MoveUserExerciseDTO
    {
        return new MoveUserExerciseDTO(
            $this->input('id'),
            $this->input('type')
        );
    }
}