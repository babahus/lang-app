<?php

namespace App\Services;

use App\Models\PairExercise;

final class PairExerciseService
{
    public function updatePairExercise(int $id, array $data): bool
    {
        $pairExercise = PairExercise::findOrFail($id);

        if ($pairExercise === null) {
            return false;
        }

        $pairExercise->correct_pair_json = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $pairExercise->save();
    }

    public function deletePairExercise(PairExercise $pairExercise ): bool
    {
        $pairExercise = PairExercise::findOrFail($pairExercise->id);

        if ($pairExercise === null) {
            return false;
        }

        return $pairExercise->delete();
    }
}
