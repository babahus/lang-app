<?php

namespace App\Http\Requests\ProgressExercise;

use App\DataTransfers\ProgressExercise\DeleteProgressDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DeleteProgressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('delete-progress-exercise', auth()->user());
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'exercise_id' => 'required|numeric|exists:accounts_exercises,id|exists:progress_exercise,accounts_exercise_id',
            'user_id' => 'required|numeric|exists:users,id|exists:progress_exercise,account_id',
        ];
    }

    public function getDTO() : DeleteProgressDTO
    {
        return new DeleteProgressDTO(
            $this->input('user_id'),
            $this->input('exercise_id'),
        );
    }
}
