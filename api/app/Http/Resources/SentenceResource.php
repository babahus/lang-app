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
            'data' => $this->getCollection()->transform(function ($sentence) {
                return [
                    'id' => $sentence->id,
                    'sentence_with_gaps' => $sentence->sentence_with_gaps,
                    'correct_answers_json' => json_decode($sentence->correct_answers_json, true),
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
