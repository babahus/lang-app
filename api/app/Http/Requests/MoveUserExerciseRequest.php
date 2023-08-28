<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use App\DataTransfers\MoveUserExerciseDTO;
use App\Enums\ExercisesTypes;
use App\Http\Response\ApiResponse;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\User;
use App\Rules\StageBelongsToCourse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
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
            'id' => match (ExercisesTypes::inEnum($this->input('exercise_type'))){
                ExercisesTypes::COMPILE_PHRASE => ['required', 'numeric', Rule::exists('compile_phrases', 'id')],
                ExercisesTypes::DICTIONARY => ['required', 'numeric', Rule::exists('dictionaries', 'id')],
                ExercisesTypes::AUDIT => ['required', 'numeric', Rule::exists('audits', 'id')],
                ExercisesTypes::PAIR_EXERCISE => ['required', 'numeric', Rule::exists('pair_exercises', 'id')],
                ExercisesTypes::PICTURE_EXERCISE => ['required', 'numeric', Rule::exists('picture_exercises', 'id')],
                ExercisesTypes::SENTENCE => ['required', 'numeric', Rule::exists('sentence', 'id')],
                default => 'nullable'
            },
            'exercise_type' => ['required', 'string', new Enum(ExercisesTypes::class)],
        ];

        if (empty($this->input('course_id')) && empty($this->input('stage_id'))) {
            $rules['account_id'] = [
                'required',
                'numeric',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::findOrFail($value);

                    if ($user->id !== $this->user()->id){
                        $fail('You can only add exercise to yourself');
                    }

                    if (!$user->hasRole('User')) {
                        $fail('The selected student must have the User role.');
                    }
                }
            ];
        }

        return $rules;
    }

    public function getDTO(): MoveUserExerciseDTO
    {
        $account_id = $this->input('account_id');

        if ($this->user()->hasRole('User') || empty($account_id)) {
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
