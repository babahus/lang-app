<?php

namespace App\Contracts;

use App\DataTransfers\Courses\CreateCourseDTO;
use App\Models\Course;

interface CourseContract
{
    public function create(CreateCourseDTO $createCourseDTO) : Course;
    public function show(int $id) : Course;
    public function update(CreateCourseDTO $createCourseDTO , int $id) : ?Course;
    public function delete(int $id) : bool;

}
