<?php

namespace App\DataTransfers;

use App\Contracts\DTO;

class MoveUserExerciseDTO implements DTO
{
    public readonly int $id;
    public readonly string $type;
    public readonly ?int $stage_id;
    public readonly ?int $course_id;
    public readonly ?int $account_id;

    public function __construct(
        int $id,
        string $type,
        ?int $stage_id,
        ?int $course_id,
        ?int $account_id
    )
    {
        $this->id = $id;
        $this->type = $type;
        $this->stage_id = $stage_id;
        $this->course_id = $course_id;
        $this->account_id = $account_id;
    }
}
