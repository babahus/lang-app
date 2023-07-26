<?php

namespace App\Http\Requests\Courses;

use App\DataTransfers\Courses\CourseActionDTO;
use Illuminate\Foundation\Http\FormRequest;

class CourseActionRequest extends FormRequest
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
            'studentId' => 'required|integer|exists:users,id',
            'courseId' => 'required|integer|exists:accounts_courses,id',
        ];
    }

    public function validatedDTO(): CourseActionDTO
    {
        return new CourseActionDTO(
            $this->validated()['studentId'],
            $this->validated()['courseId'],
        );
    }
}
