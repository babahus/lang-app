<?php

namespace App\Enums;

enum ExercisesTypes : string
{
    case COMPILE_PHRASE = 'compile_phrase';
    case DICTIONARY = 'dictionary';

    public static function inEnum(string $type): ExercisesTypes|bool
    {
        return match($type) {
            'compile_phrase' => self::COMPILE_PHRASE,
            'dictionary' => self::DICTIONARY,
            default => false,
        };
    }
}
