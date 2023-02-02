<?php

namespace App\Enums;

enum ExercisesTypes : string
{
    case COMPILE_PHRASE = 'compile_phrase';
    case DICTIONARY = 'dictionary';
    case AUDIT = 'audit';

    public static function inEnum(string $type): ExercisesTypes|bool
    {
        return match($type) {
            'compile_phrase' => self::COMPILE_PHRASE,
            'dictionary' => self::DICTIONARY,
            'audit' => self::AUDIT,
            default => false,
        };
    }
}
