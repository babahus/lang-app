<?php

namespace App\Http\Resources\ExerciseTypePaginationResources;

use App\Http\Resources\PairExerciseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PairExercisePaginationResource extends JsonResource
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
            'data' => $this->getCollection()->transform(function ($pair) {
                return [
                    'id' => $pair->id,
                    'exercises_id' => $pair->exercise->id,
                    'options' => $this->shuffleOptions($pair->correct_pair_json),
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

    private function shuffleOptions($correctPairJson)
    {
        $options = json_decode($correctPairJson, true);

        $wordToTranslation = array_reduce($options, function ($carry, $item) {
            $carry[$item['word']] = $item['translation'];
            return $carry;
        }, []);

        $shuffledTranslations = array_values($wordToTranslation);
        shuffle($shuffledTranslations);

        $shuffledOptions = array_combine(array_keys($wordToTranslation), $shuffledTranslations);

        return array_map(function ($word, $translation) {
            return ['word' => $word, 'translation' => $translation];
        }, array_keys($shuffledOptions), $shuffledOptions);
    }
}
