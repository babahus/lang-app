<?php

namespace App\Services;

use App\DataTransfers\SolvingExerciseDTO;
use App\Models\CompilePhrase;
use Carbon\Carbon;

class CompilePhraseService
{

    public function solveCompilePhrase(SolvingExerciseDTO $solvingExerciseDTO)
    {
        $compilePhrase = CompilePhrase::whereId($solvingExerciseDTO->id)->first();
        if ($compilePhrase->phrase == $solvingExerciseDTO->data)
        {
            $compilePhrase->exercises()->update(['solved' => true, 'user_exercise_type.updated_at' => Carbon::now()]);
            return true;
        }
        return false;
    }

}
