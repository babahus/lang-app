<?php

namespace App\Services;

use App\Contracts\CourseContract;
use App\DataTransfers\Courses\CourseActionDTO;
use App\DataTransfers\Courses\CreateCourseDTO;
use App\Models\Course;
use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Gate;

class CourseService implements CourseContract {

    public function create(CreateCourseDTO $createCourseDTO): Course {
        return Course::create([
           'title'       => $createCourseDTO->title,
           'description' => $createCourseDTO->description,
           'price'       => $createCourseDTO->price,
           'account_id'  => auth()->id()
        ]);
    }

    public function show(int $id): Course {

        return Course::findOrFail($id);
    }

    public function update(CreateCourseDTO $createCourseDTO, int $id): ?Course {
        Course::where('id', $id)->update([
            'title'       => $createCourseDTO->title,
            'description' => $createCourseDTO->description,
            'price'       => $createCourseDTO->price
        ]);

        return $this->show($id);
    }

    public function delete(int $id): bool {
        $course = $this->show($id);

        return $course->delete();
    }

    /**
     * @param CourseActionDTO $courseActionDTO
     * @return bool
     */
    public function attach(CourseActionDTO $courseActionDTO): bool
    {
        $course = Course::findOrFail($courseActionDTO->courseId);

        $student = User::find($courseActionDTO->studentId);

        if ($course->students->contains($student)) {
            return false;
        }

        if ($course->price === 0 || !$course->students->contains($student)) {

            return $this->addStudentToCourse($student, $course);
        }

        return false;
    }

    public function addStudentToCourse(User $student, Course $course): bool
    {
        $course->students()->attach($student->id, ['added_at' => now()]);

        return true;
    }

    /**
     * @param CourseActionDTO $courseActionDTO
     * @return bool
     */
    public function detach(CourseActionDTO $courseActionDTO): bool
    {
        $course = Course::findOrFail($courseActionDTO->courseId);

        $course->students()->detach($courseActionDTO->studentId);

        return true;
    }

    public function purchased(int $courseId): bool
    {
        $course = Course::findOrFail($courseId);

        $course->students()->updateExistingPivot(auth()->id(), ['purchased_at' => now()]);

        return true;
    }
}
