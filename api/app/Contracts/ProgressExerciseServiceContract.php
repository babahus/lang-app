<?php

namespace App\Contracts;



use App\DataTransfers\ProgressExercise\DeleteProgressDTO;
use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Exercise;

interface ProgressExerciseServiceContract
{
    public function getUserCompletedExercises(int $user_id): \Illuminate\Database\Eloquent\Collection;
    public function deleteUserProgress(DeleteProgressDTO $deleteProgressRequest): bool;
    public function getProgressByStage($userId, $stageId): array|string;
    public function solveExercise(SolvingExerciseDTO $solvingExerciseDTO, Exercise $exercise, string $correctAnswer): bool|string;
}
