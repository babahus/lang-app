<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class DeleteExerciseDTO implements DTO
{
    public readonly string $type;
    public readonly string|null $data;

    public function __construct(
        $type,
        $data
    )
    {
        $this->data = $data;
        $this->type = $type;
    }
}
