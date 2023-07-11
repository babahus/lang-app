<?php

namespace App\Http\Requests\Stages;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class CourseIdRequest extends FormRequest
{
    public function authorize()
    {
        $course = Course::findOrFail($this->route('course_id'));

        return $course->account_id === auth()->id();
    }

    public function rules()
    {
        return [
            
        ];
    }
}
