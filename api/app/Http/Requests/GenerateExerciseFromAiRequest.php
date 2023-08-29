<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\CreateExerciseDTO;
use App\DataTransfers\GenerateExerciseFromAiDTO;
use App\Enums\ExercisesTypes;
use Illuminate\Validation\Rules\Enum;

class GenerateExerciseFromAiRequest extends BaseRequest
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
        ];
    }

    public function getDTO(): GenerateExerciseFromAiDTO
    {
        return new GenerateExerciseFromAiDTO(null, $this->input('type'), null);
    }

    public function setDTO(DTO $dto, array $data): DTO
    {
        $dto->data = $data['data'] ?? null;
        $dto->additional_data = $data['additional_data'] ?? null;

        return $dto;
    }
}
