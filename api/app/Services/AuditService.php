<?php

namespace App\Services;

use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Audit;
use App\Models\Exercise;
use App\Models\ProgressExercise;
use Carbon\Carbon;

final class AuditService
{
    protected ProgressExerciseService $progressExerciseService;

    public function __construct(ProgressExerciseService $progressExerciseService)
    {
        $this->progressExerciseService = $progressExerciseService;
    }

    public function solveAudit(SolvingExerciseDTO $solvingExerciseDTO, Exercise $exercise): bool|string
    {
        $correctAnswer = $exercise->audit->transcription;

        if ($correctAnswer !== $solvingExerciseDTO->data)
        {

            return 'Incorrect answer, try again';
        }

        return $this->progressExerciseService->solveExercise($exercise);
    }
}
