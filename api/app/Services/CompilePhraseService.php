<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\ProgressExercise;
use App\Models\User;
use Carbon\Carbon;
use App\Models\CompilePhrase;
use App\DataTransfers\SolvingExerciseDTO;

final class CompilePhraseService
{
    protected ProgressExerciseService $progressExerciseService;

    public function __construct(ProgressExerciseService $progressExerciseService)
    {
        $this->progressExerciseService = $progressExerciseService;
    }

    public function solveCompilePhrase(SolvingExerciseDTO $solvingExerciseDTO, Exercise $exercise): bool|string
    {
        $correctAnswer = $exercise->compilePhrase->phrase;

        if ($correctAnswer !== $solvingExerciseDTO->data)
        {

            return 'Incorrect answer, try again';
        }

        return $this->progressExerciseService->solveExercise($exercise);
    }
}
