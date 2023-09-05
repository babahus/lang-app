<?php

namespace App\Services;

use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Audit;
use App\Models\Exercise;
use App\Models\ProgressExercise;
use Carbon\Carbon;

final class AuditService
{
    protected $progressExerciseService;

    public function __construct(ProgressExerciseService $progressExerciseService)
    {
        $this->progressExerciseService = $progressExerciseService;
    }
    public function solveAudit(SolvingExerciseDTO $solvingExerciseDTO, Exercise $exercise)
    {
        $correctAnswer = $exercise->audit->transcription;

        return $this->progressExerciseService->solveExercise($solvingExerciseDTO, $exercise, $correctAnswer);
    }
}
