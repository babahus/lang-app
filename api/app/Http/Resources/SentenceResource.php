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
        $correctAnswers = json_decode($this->correct_answers_json, true);
        $sentenceWithGaps = $this->sentence_with_gaps;

        foreach ($correctAnswers as $answer) {
            $sentenceWithGaps = str_replace($answer, '__', $sentenceWithGaps);
        }

        return [
            'id' => $this->id,
            'sentence_with_gaps' => $sentenceWithGaps,
            'correct_answers_json' => $correctAnswers,
        ];
    }
}
