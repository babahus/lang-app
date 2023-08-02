<?php

namespace App\Enums;

enum ExercisesTypes : string
{
    case COMPILE_PHRASE = 'compile_phrase';
    case DICTIONARY = 'dictionary';
    case AUDIT = 'audit';
    case  PAIR_EXERCISE = 'pair_exercise';

    public static function inEnum(string $type): ExercisesTypes|bool
    {
        return match($type) {
            'compile_phrase' => self::COMPILE_PHRASE,
            'dictionary' => self::DICTIONARY,
            'audit' => self::AUDIT,
            'pair_exercise' => self::PAIR_EXERCISE,
            default => false,
        };
    }
}
