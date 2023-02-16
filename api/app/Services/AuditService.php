<?php

namespace App\Services;

use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Audit;

class AuditService
{

    public function solveAudit(SolvingExerciseDTO $solvingExerciseDTO)
    {
        $audit = Audit::whereId($solvingExerciseDTO->id)->first();
        if ($audit->transcription == $solvingExerciseDTO->data)
        {
            return true;
        }
        return false;
    }

}
