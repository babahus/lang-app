<?php

namespace App\Enums\Generated;

enum ExerciseGeneratedTypes: string
{
    //todo : For current moment, we would use only compile_phrase type for generating exercise

    public static function getMessageTemplate(string $type): string|bool
    {
        return match ($type) {
            'compile_phrase' => 'Generate %d exercises for sentence composition. It\'s must be a string sentence, with only one key "phrase"(For example, { phrase : "Example sentence composition"}. Also its must be array(For example [{phrase: "test"},{phrase : "test2"}]). Please provide a %s output as I will be saving this data in an SQL table.',
            'audit' => 'Generate %d audit tasks. Please provide a %s output as I will be saving this data in an SQL table.',
            default => false
        };
    }
}
