<?php

namespace App\Enums;

enum ExercisesTypes : string
{
    case COMPILE_PHRASE = 'compile_phrase';
    case DICTIONARY = 'dictionary';
    case AUDIT = 'audit';
    case  PAIR_EXERCISE = 'pair_exercise';
    case  PICTURE_EXERCISE = 'picture_exercise';
    case SENTENCE = 'sentence';

    public static function inEnum(string $type): ExercisesTypes|bool
    {
        return match($type) {
            'compile_phrase'   => self::COMPILE_PHRASE,
            'dictionary'       => self::DICTIONARY,
            'audit'            => self::AUDIT,
            'pair_exercise'    => self::PAIR_EXERCISE,
            'picture_exercise' => self::PICTURE_EXERCISE,
            'sentence'         => self::SENTENCE,
            default => false,
        };
    }

    public static function allValues(): array
    {
        return [
            self::COMPILE_PHRASE,
            self::DICTIONARY,
            self::AUDIT,
            self::PAIR_EXERCISE,
            self::PICTURE_EXERCISE,
            self::SENTENCE,
        ];
    }
}
