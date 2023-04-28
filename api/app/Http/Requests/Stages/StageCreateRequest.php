<?php

namespace App\Http\Requests\Stages;

use App\Models\Course;
use App\Models\Stage;
use Illuminate\Foundation\Http\FormRequest;

class StageCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $course = Course::findOrFail($this->course_id);

        return $course->account_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id'   => 'required|exists:courses,id',
            'description' => 'required|string|max:255',
            'title'       => 'required|string|max:255',
        ];
    }
}
