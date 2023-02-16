<?php

namespace App\Services;

use App\DataTransfers\SolvingExerciseDTO;
use App\Models\CompilePhrase;

class CompilePhraseService
{

    public function solveCompilePhrase(SolvingExerciseDTO $solvingExerciseDTO)
    {
        $compilePhrase = CompilePhrase::whereId($solvingExerciseDTO->id)->first();
        if ($compilePhrase->phrase == $solvingExerciseDTO->data)
        {
            return true;
        }
        return false;
    }

}
