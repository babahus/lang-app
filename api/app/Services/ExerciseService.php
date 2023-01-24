<?php

namespace App\Services;

use App\Contracts\ExerciseServiceContract;
use App\Enums\ExercisesTypes;
use App\Models\CompilePhrase;
use App\Models\Dictionary;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ExerciseService implements ExerciseServiceContract
{

    public function getAllExercises(int $userId): \Illuminate\Database\Eloquent\Collection|array
    {
        return User::with(['dictionary','compilePhrase'])->where('id', $userId)->get();
    }

    public function getExercisesByType(string $type, int $userId): \Illuminate\Contracts\Pagination\LengthAwarePaginator|bool
    {
        return match (ExercisesTypes::tryFrom($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::with('exercises')
                ->whereRelation('exercises',['user_id' => $userId])
                ->paginate(10),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::paginate(10),
            default => false
        };
    }

    public function getExerciseByIdAndType(string $type, int $id, int $userId): CompilePhrase|Dictionary|bool
    {
        return match (ExercisesTypes::tryFrom($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::with('exercises')
                ->whereRelation('exercises',['user_id' => $userId, 'exercise_id' => $id])
                ->first(),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)->first(),
            default => false
        };
    }
}
