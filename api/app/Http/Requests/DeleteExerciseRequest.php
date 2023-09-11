<?php

namespace App\Http\Requests;

use App\DataTransfers\DeleteExerciseDTO;
use App\Enums\ExercisesTypes;
use App\Rules\Exercise\DeleteExercise\ExerciseDataRule;
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
            'data' => ['nullable', new ExerciseDataRule()],
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
