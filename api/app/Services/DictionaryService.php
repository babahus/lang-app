<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Dictionary;
use App\DataTransfers\SolvingExerciseDTO;
use App\Contracts\DictionaryServiceContract;

final class DictionaryService implements DictionaryServiceContract
{

    public function createEmptyDictionary(): Dictionary
    {
        return Dictionary::create(['dictionary' => '[]']);
    }

    public function fillDictionary(SolvingExerciseDTO $solvingExerciseDTO): Dictionary
    {
        $dictionary = Dictionary::whereId($solvingExerciseDTO->id)->first();
        $json = json_decode($dictionary->dictionary, true);
        $json[] = json_decode($solvingExerciseDTO->data, true);
        $dictionary->exercises()->update(['solved' => true, 'user_exercise_type.updated_at' => Carbon::now()]);
        $dictionary->dictionary = json_encode($json);
        $dictionary->save();

        return $dictionary;
    }

    public function updateDictionary(Dictionary $dictionary, array|string $data): bool
    {
        $transformedData = json_decode($data, true);

        $decodedArrJson = json_decode($dictionary->dictionary, true);

        foreach ($transformedData as $newWord) {
            $wordFound = false;

            foreach ($decodedArrJson as &$item) {
                if ($item['word'] === $newWord['word']) {
                    $item['translation'] = $newWord['translation'];
                    $wordFound = true;
                    break;
                }
            }

            if (!$wordFound) {
                $decodedArrJson[] = $newWord;
            }
        }

        $updatedData = json_encode($decodedArrJson, JSON_UNESCAPED_UNICODE);

        $dictionary->setAttribute('dictionary', $updatedData);

        return $dictionary->save();
    }

    public function deleteDictionary(Dictionary $dictionary, array|string $data): bool
    {
        $data = json_decode($data, true);

        $jsonArray = json_decode($dictionary->dictionary, true);

        foreach ($data as $wordToDelete) {
            foreach ($jsonArray as $index => $item) {
                if ($item['word'] === $wordToDelete) {
                    unset($jsonArray[$index]);
                }
            }
        }

        $jsonArray = array_values($jsonArray);

        $updatedData = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);

        $dictionary->setAttribute('dictionary', $updatedData);

        return $dictionary->save();
    }
}
