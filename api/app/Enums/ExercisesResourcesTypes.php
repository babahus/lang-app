<?php

namespace App\Enums;

enum ExercisesResourcesTypes : string
{
    case  COMPILE_PHRASE = 'App\Models\CompilePhrase';
    case  DICTIONARY = 'App\Models\Dictionary';
    case  AUDIT = 'App\Models\Audit';
    case  PAIR_EXERCISE = 'App\Models\PairExercise';

}
