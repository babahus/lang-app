<?php

namespace App\DataTransfers;

use App\Contracts\DTO;
use Illuminate\Http\UploadedFile;

class CreateExerciseDTO implements DTO
{
    public readonly string|UploadedFile|null $data;
    public readonly string $type;
    public ?string $transcript;
    public ?string $option_json;
    public ?string $correct_answers_json;

    public function __construct(
        $data,
        $type,
        ?string $transcript = null,
        ?string $option_json = null,
        ?string $correct_answers_json = null,
    )
    {
        $this->data = $data;
        $this->type = $type;
        $this->transcript = $transcript;
        $this->option_json = $option_json;
        $this->correct_answers_json = $correct_answers_json;
    }
}
