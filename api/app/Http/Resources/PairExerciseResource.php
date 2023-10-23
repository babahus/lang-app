<?php

namespace App\Http\Resources;

use App\Models\PairExercise;
use Illuminate\Http\Resources\Json\JsonResource;

final class PairExerciseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'data' => $this->getCollection()->transform(function ($pair) {
                return [
                    'id' => $pair->id,
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
