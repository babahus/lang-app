<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class CreateExerciseDTO implements DTO
{
    public readonly string|UploadedFile $data;
    public readonly string $type;
    public readonly string $transcript;

    public function __construct(
        $data,
        $type,
        $transcript
    )
    {
        $this->data = $data;
        $this->type = $type;
        $this->transcript = $transcript;
    }
}
