<?php

namespace App\Enums\Generated;

enum ExerciseGeneratedTypes: string
{
    //todo : For current moment, we would use only compile_phrase type for generating exercise

    public static function getMessageTemplate(string $type): string|bool
    {
        return match ($type) {
            'compile_phrase' => 'Generate %d tasks in tabular form for compile phrase. It\'s must be a string sentence, with only one key "phrase". Please provide a %s output as I will be saving this data in an SQL table.',
            'audit' => 'Generate %d audit tasks. Please provide a %s output as I will be saving this data in an SQL table.',
            default => false
        };
    }
}
