<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class CreateExerciseDTO implements DTO
{
    public readonly string|UploadedFile $data;
    public readonly string $type;
    public ?string $transcript;
    public ?string $option_json;

    public function __construct(
        $data,
        $type,
        ?string $transcript = null,
        ?string $option_json = null
    )
    {
        $this->data = $data;
        $this->type = $type;
        $this->transcript = $transcript;
        $this->option_json = $option_json;
    }
}
