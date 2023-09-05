<?php

namespace App\Services;

final class PictureExerciseService
{
    protected $progressExerciseService;

    public function __construct(ProgressExerciseService $progressExerciseService)
    {
        $this->progressExerciseService = $progressExerciseService;
    }

    public function solvePicture($solvingExerciseDTO, $exercise): bool|string
    {
        $correctAnswerJson = $exercise->pictureExercise->option_json;
        $correctAnswerArr = json_decode($correctAnswerJson);

        $correctAnswer = '';
        foreach ($correctAnswerArr as $correctAnswers)
        {
            if ($correctAnswers->text === $solvingExerciseDTO->data && $correctAnswers->is_correct == true){
                $correctAnswer = $correctAnswers->text;
            }
        }

        return $this->progressExerciseService->solveExercise($solvingExerciseDTO, $exercise, $correctAnswer);
    }
}
