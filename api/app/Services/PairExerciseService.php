<?php

namespace App\Services;

use App\Contracts\PairExerciseServiceContract;
use App\Models\PairExercise;

final class PairExerciseService implements PairExerciseServiceContract
{
    public function updatePairExercise(int $id, array $data): bool
    {
        $pairExercise = PairExercise::findOrFail($id);

        $pairExercise->correct_pair_json = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $pairExercise->save();
    }

    public function deletePairExercise(PairExercise $pairExercise): bool
    {
        $pairExercise = PairExercise::findOrFail($pairExercise->id);

        return $pairExercise->delete();
    }
}