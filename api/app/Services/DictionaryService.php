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
        $transformedData = json_decode(str_replace("'", '"', $data), true);
        $decodedArrJson = json_decode($dictionary->dictionary, true);

        // Loop through each associative array and update the 'translate' key value
        foreach ($decodedArrJson as &$item) {

            if ($item['word'] == $transformedData['word']) {
                $item['translate'] = $transformedData['translate'];
            }
        }
        // Encode the updated array back into a JSON string
        $updatedData = json_encode($decodedArrJson, JSON_UNESCAPED_UNICODE);
        // Print the updated JSON string
        $dictionary->dictionary = $updatedData;

        return $dictionary->save();
    }

    public function deleteDictionary(Dictionary $dictionary, array|string $data): bool
    {
        $data = json_decode($data , true);
        $jsonArray = json_decode($dictionary->dictionary, true);
        $index = array_search($data, $jsonArray);

        if ($index !== false) {
            unset($jsonArray[$index]);
        }
        $dictionary->dictionary = array_values($jsonArray);

        return $dictionary->save();
    }
}
