<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class CreateExerciseDTO implements DTO
{
    public readonly string $data;
    public readonly string $type;

    public function __construct(
        $data,
        $type
    )
    {
        $this->data = $data;
        $this->type = $type;
    }
}
