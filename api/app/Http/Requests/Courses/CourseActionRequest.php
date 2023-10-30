<?php

namespace App\Http\Requests\Courses;

use App\DataTransfers\Courses\CourseActionDTO;
use App\Http\Requests\BaseRequest;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class CourseActionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws ValidationException
     */
    public function authorize()
    {
        if ($this->input('courseId') === null || $this->input('studentId') === null) {
            return false;
        }

        $course = Course::findOrFail($this->input('courseId'));
        $studentId = $this->input('studentId');

        if ($studentId == $course->account_id){
            $validator = Validator::make([], []);
            $validator->errors()->add('courseId', 'A course creator cannot subscribe to their own course');
            throw new ValidationException($validator);
        }

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
