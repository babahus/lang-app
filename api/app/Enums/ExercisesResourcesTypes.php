<?php

namespace App\Enums;

enum ExercisesResourcesTypes : string
{
    case  COMPILE_PHRASE = 'App\Models\CompilePhrase';
//    case  DICTIONARY = 'App\Models\Dictionary';
    case  AUDIT = 'App\Models\Audit';
    case  PAIR_EXERCISE = 'App\Models\PairExercise';
    case  PICTURE_EXERCISE = 'App\Models\PictureExercise';
    case  SENTENCE = 'App\Models\Sentence';

    public static function inEnum(string $type)
    {
        return match($type) {
            'COMPILE_PHRASE'    => self::COMPILE_PHRASE,
            'AUDIT'            => self::AUDIT,
            'PAIR_EXERCISE'     => self::PAIR_EXERCISE,
            'PICTURE_EXERCISE'  => self::PICTURE_EXERCISE,
            'SENTENCE'         => self::SENTENCE,
            default => false,
        };
    }

    public static function getExerciseRelationshipName($type)
    {
        if ($type === self::COMPILE_PHRASE) {
            return 'compilePhrase';
        } elseif ($type === self::AUDIT) {
            return 'audit';
        } elseif ($type === self::PAIR_EXERCISE) {
            return 'pairExercise';
        } elseif ($type === self::PICTURE_EXERCISE) {
            return 'pictureExercise';
        } elseif ($type === self::SENTENCE) {
            return 'sentence';
        };
    }
}
