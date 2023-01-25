<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class DeleteExerciseDTO implements DTO
{
    public readonly string $id;
    public readonly string $type;

    public function __construct(
        $type
    )
    {
        $this->type = $type;
    }
}
