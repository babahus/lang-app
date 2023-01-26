<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class MoveUserExerciseDTO implements DTO
{
    public readonly string $id;
    public readonly string $type;


    public function __construct(
        $id,
        $type,
    )
    {
        $this->id = $id;
        $this->type = $type;
    }
}
