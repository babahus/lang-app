<?php

namespace App\DataTransfers\Courses;

use App\Contracts\DTO;

class CreateCourseDTO implements DTO
{
    public readonly string $title;
    public readonly string $description;
    public readonly int $price;

    public function __construct(
        $title,
        $description,
        $price
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
    }
}
