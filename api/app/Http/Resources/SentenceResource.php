<?php

namespace App\Http\Resources;

use App\Enums\ExercisesResourcesTypes;
use App\Enums\ExercisesTypes;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

final class SentenceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sentence_with_gaps' => $this->sentence_with_gaps,
            'correct_answers_json' => json_decode($this->correct_answers_json, true),
        ];
    }
}
