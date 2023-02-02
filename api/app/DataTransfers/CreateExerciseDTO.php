<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class CreateExerciseDTO implements DTO
{
    public readonly string|UploadedFile $data;
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
