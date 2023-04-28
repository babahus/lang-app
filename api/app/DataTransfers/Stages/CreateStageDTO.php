<?php

namespace App\DataTransfers\Stages;

use App\Contracts\DTO;

class CreateStageDTO implements DTO
{
    public readonly string $title;
    public readonly string $description;
    public readonly ?int $course_id;

    public function __construct(
        $title,
        $description,
        $course_id,
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->course_id = $course_id;
    }
}
