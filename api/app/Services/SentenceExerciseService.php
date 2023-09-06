<?php

namespace App\Services;

use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Exercise;
use App\Models\ProgressExercise;

final class SentenceExerciseService
{
    protected ProgressExerciseService $progressExerciseService;

    public function __construct(ProgressExerciseService $progressExerciseService)
    {
        $this->progressExerciseService = $progressExerciseService;
    }

    public function solveSentence(SolvingExerciseDTO $solvingExerciseDTO, Exercise $exercise): bool|string
    {
        $correctAnswerJson = $exercise->sentence->correct_answers_json;

        $userDataJson = $solvingExerciseDTO->data;

        $difference = array_diff(json_decode($userDataJson), json_decode($correctAnswerJson));

        if (!empty($difference)){

            return 'Incorrect answer, try again';
        }

        return $this->progressExerciseService->solveExercise($exercise);
    }
}
