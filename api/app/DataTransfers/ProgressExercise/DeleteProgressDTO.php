<?php

namespace App\DataTransfers\ProgressExercise;

use App\Contracts\DTO;

class DeleteProgressDTO implements DTO
{
    public readonly int $user_id;
    public readonly int $exercise_id;

    public function __construct($user_id, $exercise_id)
    {
        $this->user_id = $user_id;
        $this->exercise_id = $exercise_id;
    }
}
