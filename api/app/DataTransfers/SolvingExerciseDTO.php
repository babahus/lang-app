<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class SolvingExerciseDTO implements DTO
{
    public readonly int $id;
    public readonly string $type;
    public readonly string $data;
    public readonly int $exercise_id;

    public function __construct(
        $id,
        $type,
        $data,
        $exercise_id
    )
    {
        $this->id   = $id;
        $this->type = $type;
        $this->data = $data;
        $this->exercise_id = $exercise_id;
    }
}
