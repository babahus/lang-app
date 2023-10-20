<?php

namespace App\Http\Requests;

use App\DataTransfers\MoveUserExerciseDTO;
use App\Enums\ExercisesTypes;
use App\Models\User;
use App\Rules\Exercise\MoveUserExerciseAccountIdRule;
use App\Rules\Exercise\MoveUserExerciseIdRule;
use App\Rules\StageBelongsToCourse;
use Illuminate\Support\Facades\Gate;
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
        return Gate::allows('attach-detach-exercise', [$this->getDTO()]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'stage_id' => ['nullable', 'numeric', new StageBelongsToCourse($this->input('course_id'))],
            'course_id' => ['nullable', 'numeric', Rule::exists('accounts_courses', 'id')],
            'id' => ['required', new MoveUserExerciseIdRule()],
            'exercise_type' => ['required', 'string', new Enum(ExercisesTypes::class)],
        ];

        if (empty($this->input('course_id')) && empty($this->input('stage_id'))) {
            $rules['account_id'] = ['nullable', 'numeric', 'exists:users,id', new MoveUserExerciseAccountIdRule()];
        }

        return $rules;
    }

    public function getDTO(): MoveUserExerciseDTO
    {
        $account_id = $this->input('account_id');

        if (empty($account_id)) {
            $account_id = $this->user()->id;
        }

        return new MoveUserExerciseDTO(
            $this->input('id'),
            $this->input('exercise_type'),
            $this->input('stage_id'),
            $this->input('course_id'),
            $account_id
         );
    }
}
