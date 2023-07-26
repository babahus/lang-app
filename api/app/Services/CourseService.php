<?php

namespace App\Services;

use App\Contracts\CourseContract;
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

    public function attach(int $studentId, int $courseId): bool
    {
        $course = Course::findOrFail($courseId);

        if (Gate::denies('canManageEnrollment', [$course, $studentId])) {
            return false;
        }

        $student = User::find($studentId);

        if ($course->students->contains($student)) {
            return false;
        }

        if ($course->price === 0 || !$course->students->contains($student)) {
            $this->addStudentToCourse($student, $course);
            return true;
        }

        return false;
    }

    public function addStudentToCourse(User $student, Course $course)
    {
        $course->students()->attach($student->id, ['added_at' => now()]);

        return true;
    }

    public function detach(int $studentId, int $courseId): bool
    {
        $course = Course::findOrFail($courseId);

        if (Gate::denies('canManageEnrollment', [$course, $studentId])) {
            return false;
        }

        $course->students()->detach($studentId);

        return true;
    }

    public function purchased(int $courseId): bool
    {
        $course = Course::findOrFail($courseId);
        $course->students()->updateExistingPivot(auth()->id(), ['purchased_at' => now()]);

        return true;
    }
}
