<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class CreateExerciseDTO implements DTO
{
    public readonly string|UploadedFile $data;
    public readonly string $type;
    public ?string $transcript; 

    public function __construct(
        $data,
        $type,
        ?string $transcript = null
    )
    {
        $this->data = $data;
        $this->type = $type;
        $this->transcript = $transcript;
    }
}
