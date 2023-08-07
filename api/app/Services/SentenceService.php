<?php

namespace App\Services;

use App\Models\PictureExercise;
use App\Models\Sentence;

final class SentenceService
{
    public function updateSentence(int $id, string $data): bool
    {
        $sentenceExercise = Sentence::findOrFail($id);

        $sentenceExercise->sentence_with_gaps = $data;

        return $sentenceExercise->save();
    }

    public function deleteSentence(Sentence $sentenceExercise ): bool
    {
        $sentenceExercise = Sentence::findOrFail($sentenceExercise->id);

        return $sentenceExercise->delete();
    }
}
