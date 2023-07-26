<?php

namespace App\DataTransfers\Courses;

use App\Contracts\DTO;

class CourseActionDTO implements DTO
{
    public readonly int $studentId;
    public readonly int $courseId;

    public function __construct(
        $studentId,
        $courseId,
    )
    {
        $this->studentId = $studentId;
        $this->courseId = $courseId;
    }
}
