<?php

namespace App\Contracts;

use App\DataTransfers\Courses\CreateCourseDTO;
use App\Models\Course;
use App\Models\User;

interface CourseContract
{
    public function create(CreateCourseDTO $createCourseDTO) : Course;
    public function show(int $id) : Course;
    public function update(CreateCourseDTO $createCourseDTO , int $id) : ?Course;
    public function delete(int $id) : bool;
    public function attach(int $studentId, int $courseId): bool;
    public function addStudentToCourse(User $student, Course $course);
    public function detach(int $studentId, int $courseId): bool;
    public function purchased(int $courseId): bool;
}
