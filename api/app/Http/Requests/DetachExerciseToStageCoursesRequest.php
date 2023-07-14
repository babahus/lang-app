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

final class DetachExerciseToStageCoursesRequest extends BaseRequest
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
            'stage_id' => ['required', 'numeric', Rule::exists('accounts_courses_stages', 'id')],
            'course_id' => ['required', 'numeric', Rule::exists('accounts_courses', 'id')],
            'id' => match (ExercisesTypes::inEnum($this->input('exercise_type'))){
                ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrases', 'id')],
                ExercisesTypes::DICTIONARY => ['required', 'numeric', Rule::exists('dictionaries', 'id')],
                ExercisesTypes::AUDIT => ['required', 'numeric', Rule::exists('audits', 'id')],
                default => 'nullable'
            },
            'exercise_type' => ['required', 'string', new Enum(ExercisesTypes::class)]
        ];
    }

    public function getDTO(): MoveUserExerciseDTO
    {
        return new MoveUserExerciseDTO(
            $this->input('id'),
            $this->input('exercise_type')
        );
    }
}