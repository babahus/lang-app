<?php

namespace App\Http\Requests\Courses;

use App\DataTransfers\Courses\CourseActionDTO;
use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;

final class CourseActionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        dd($this);
        $course = Course::findOrFail($this->input('courseId'));
        $studentId = $this->input('studentId');

        return Gate::allows('canManageEnrollment', [$course, $studentId]);
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

    public function getDTO(): CourseActionDTO
    {
        return new CourseActionDTO(
            $this->input()['studentId'],
            $this->input()['courseId'],
        );
    }
}
