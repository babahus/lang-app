<?php

namespace App\Http\Requests\Courses;

use App\Contracts\DTO;
use App\DataTransfers\Courses\CreateCourseDTO;
use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $course = Course::findOrFail($this->route('course'));
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
            'title'       => 'required|string|max:225',
            'description' => 'required|string|max:255',
            'price'       => 'sometimes|numeric|max:100000'
        ];
    }

    public function getDTO(): CreateCourseDTO
    {
        return new CreateCourseDTO(
            $this->input('title'),
            $this->input('description'),
            $this->input('price') ?? 0
        );
    }
}
