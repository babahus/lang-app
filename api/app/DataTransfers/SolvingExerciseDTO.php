<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class SolvingExerciseDTO implements DTO
{
    public readonly string $id;
    public readonly string $type;
    public readonly string $data;

    public function __construct(
        $id,
        $type,
        $data
    )
    {
        $this->id   = $id;
        $this->type = $type;
        $this->data = $data;
    }
}
