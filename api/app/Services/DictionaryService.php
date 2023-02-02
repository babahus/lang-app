<?php

namespace App\Services;

use App\Contracts\DictionaryServiceContract;
use App\Models\Dictionary;

class DictionaryService implements DictionaryServiceContract
{

    public function createEmptyDictionary(): \Illuminate\Database\Eloquent\Model|Dictionary
    {
        return Dictionary::create(['dictionary' => '[]']);
    }
}
