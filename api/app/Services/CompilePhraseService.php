<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\CompilePhrase;
use App\DataTransfers\SolvingExerciseDTO;

final class CompilePhraseService
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
