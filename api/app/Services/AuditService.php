<?php

namespace App\Services;

use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Audit;
use Carbon\Carbon;

final class AuditService
{
    public function solveAudit(SolvingExerciseDTO $solvingExerciseDTO)
    {
        $audit = Audit::whereId($solvingExerciseDTO->id)->first();

        if ($audit->transcription == $solvingExerciseDTO->data)
        {
            $audit->exercises()->update(['solved' => true, 'user_exercise_type.updated_at' => Carbon::now()]);

            return true;
        }

        return false;
    }

}
