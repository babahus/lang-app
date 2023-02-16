<?php

namespace App\Services;

use App\Contracts\DictionaryServiceContract;
use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Dictionary;

class DictionaryService implements DictionaryServiceContract
{

    public function createEmptyDictionary(): Dictionary
    {
        return Dictionary::create(['dictionary' => '[]']);
    }

    public function fillDictionary(SolvingExerciseDTO $solvingExerciseDTO): Dictionary
    {
        $dictionary = Dictionary::whereId($solvingExerciseDTO->id)->first();
        $json = json_decode($dictionary->dictionary);
        $json[] = $solvingExerciseDTO->data;
        $dictionary->dictionary = json_encode($json);
        $dictionary->save();
        return $dictionary;

    }
}
