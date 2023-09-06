<?php

namespace App\Enums;

enum ExercisesResourcesTypes : string
{
    case  COMPILE_PHRASE = 'App\Models\CompilePhrase';
    case  DICTIONARY = 'App\Models\Dictionary';
    case  AUDIT = 'App\Models\Audit';
    case  PAIR_EXERCISE = 'App\Models\PairExercise';
    case  PICTURE_EXERCISE = 'App\Models\PictureExercise';
    case  SENTENCE = 'App\Models\Sentence';

    public static function inEnum(string $type)
    {
        return match($type) {
            'COMPILE_PHRASE'    => self::COMPILE_PHRASE,
            'DICTIONARY'       => self::DICTIONARY,
            'AUDIT'            => self::AUDIT,
            'PAIR_EXERCISE'     => self::PAIR_EXERCISE,
            'PICTURE_EXERCISE'  => self::PICTURE_EXERCISE,
            'SENTENCE'         => self::SENTENCE,
            default => false,
        };
    }
}
