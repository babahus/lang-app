<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class UpdateExerciseDTO implements DTO
{
    public readonly string|UploadedFile|null $data;
    public readonly string $type;
    public readonly string|array|null $additional_data;

    public function __construct(
        string|UploadedFile|null $data,
        string $type,
        string|array|null $additional_data
    )
    {
        $this->data = $data;
        $this->type = $type;
        $this->additional_data = $additional_data;
    }
}

