<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ExerciseServiceContract
{
    public function getAllExercises(int $userId);
    public function getExercisesByType(string $type, int $userId);
    public function getExerciseByIdAndType(string $type, int $id ,int $userId);

}
