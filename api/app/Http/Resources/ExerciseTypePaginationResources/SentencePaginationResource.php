<?php

namespace App\Http\Resources\ExerciseTypePaginationResources;

use Illuminate\Http\Resources\Json\JsonResource;

class SentencePaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->getCollection()->transform(function ($sentence) {
                $correctAnswers = json_decode($sentence->correct_answers_json, true);
                $sentenceWithGaps = $sentence->sentence_with_gaps;

                foreach ($correctAnswers as $answer) {
                    $sentenceWithGaps = str_replace($answer, '__', $sentenceWithGaps);
                }

                return [
                    'id' => $sentence->id,
                    'exercises_id' => $sentence->exercise ? $sentence->exercise->id : null,
                    'sentence_with_gaps' => $sentenceWithGaps,
                    'correct_answers_json' => $correctAnswers,
                    'created_at' => $sentence->created_at,
                    'updated_at' => $sentence->updated_at,
                ];
            }),
            'pagination' => [
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
                'per_page'     => $this->perPage(),
                'next_page_url'=> $this->nextPageUrl(),
                'prev_page_url'=> $this->previousPageUrl(),
                'total'        => $this->total(),
                'from'         => $this->firstItem(),
                'to'           => $this->lastItem(),
            ],
        ];
    }
}
