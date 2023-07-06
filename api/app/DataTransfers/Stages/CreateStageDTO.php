<?php

namespace App\DataTransfers\Stages;

use App\Contracts\DTO;

class CreateStageDTO implements DTO
{
    public readonly string $title;
    public readonly string $description;
    public readonly ?string $course_id;

    public function __construct(
        string $title,
        string $description,
        ?string $course_id
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->course_id = $course_id;
    }
}