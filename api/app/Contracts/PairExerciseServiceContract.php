<?php

namespace App\Contracts;

use App\Models\PairExercise;

interface PairExerciseServiceContract
{
    public function updatePairExercise(int $id, array $data): bool;
    public function deletePairExercise(PairExercise $pairExercise): bool;
    public function solvePair($solvingExerciseDTO, $exercise): bool|string;
}
