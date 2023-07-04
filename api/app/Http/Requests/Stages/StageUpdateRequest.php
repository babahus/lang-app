<?php

namespace App\Http\Requests\Stages;

use App\Models\Stage;
use App\Http\Requests\BaseRequest;
use App\Contracts\DTO;
use App\DataTransfers\Stage\CreateStageDTO;
use Illuminate\Foundation\Http\FormRequest;

class StageUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $stage = Stage::findOrFail($this->route('stage'));
        return $stage->course()->account_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id'   => 'required|exists:accounts_courses,id',
            'description' => 'required|string|max:255',
            'title'       => 'required|string|max:255',
        ];
    }

    public function getDTO(): CreateStageDTO
    {
        return new CreateStageDTO(
            $this->input('course_id'),
            $this->input('description'),
            $this->input('title')
        );
    }
}
