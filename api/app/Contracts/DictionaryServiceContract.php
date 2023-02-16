<?php

namespace App\Contracts;

use App\DataTransfers\CreateExerciseDTO;
use App\DataTransfers\DeleteExerciseDTO;
use App\DataTransfers\MoveUserExerciseDTO;
use App\DataTransfers\SolvingExerciseDTO;
use App\DataTransfers\UpdateExerciseDTO;
use App\Models\User;

interface DictionaryServiceContract
{
    public function createEmptyDictionary();
    public function fillDictionary(SolvingExerciseDTO $solvingExerciseDTO);
}
