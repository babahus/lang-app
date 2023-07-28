<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class CreateExerciseDTO implements DTO
{
    public string|UploadedFile|null $data;
    public string $type;
    public ?string $transcript;

    public function __construct(
        ?string $data,
        $type,
        ?string $transcript = null
    )
    {
        $this->data = $data;
        $this->type = $type;
        $this->transcript = $transcript;
    }
}
