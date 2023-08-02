<?php

namespace App\Http\Resources;

use App\Models\PairExercise;
use Illuminate\Http\Resources\Json\JsonResource;

final class PairExerciseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'options' => $this->shuffleOptions($this->correct_pair_json),
            'solved' => $this->pivot->solved ?? null,
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
