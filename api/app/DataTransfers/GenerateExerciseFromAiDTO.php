<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class GenerateExerciseFromAiDTO implements DTO
{
    public string|UploadedFile|null $data;
    public readonly string $type;
    public string|array|null $additional_data;

    public function __construct(
        string|UploadedFile|null $data,
        string                   $type,
        string|array|null        $additional_data
    )
    {
        $this->data = $data;
        $this->type = $type;
        $this->additional_data = $additional_data;
    }

    public function toCreateExerciseDTO(): CreateExerciseDTO {
        return new CreateExerciseDTO($this->data, $this->type, $this->additional_data);
    }
}
