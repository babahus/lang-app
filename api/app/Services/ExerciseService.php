<?php

namespace App\Services;

use App\Contracts\ExerciseServiceContract;
use App\DataTransfers\CreateExerciseDTO;
use App\DataTransfers\DeleteExerciseDTO;
use App\DataTransfers\UpdateExerciseDTO;
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
        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::with('exercises')
                ->whereRelation('exercises',['user_id' => $userId])
                ->paginate(10),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::paginate(10),
            default => false
        };
    }

    public function getExerciseByIdAndType(string $type, int $id, int $userId): CompilePhrase|Dictionary|bool
    {
        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::with('exercises')
                ->whereRelation('exercises',['user_id' => $userId, 'exercise_id' => $id])
                ->first(),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)->first(),
            default => false
        };
    }

    public function update(UpdateExerciseDTO $updateExerciseDTO, int $id): bool|int
    {
        return match (ExercisesTypes::inEnum($updateExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => Dictionary::whereId($id)
                     ->update(['dictionary' => $updateExerciseDTO->data, 'updated_at' => now()]),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)
                     ->update(['phrase' => $updateExerciseDTO->data, 'updated_at' => now()]),
            default => false
        };
    }

    public function delete(DeleteExerciseDTO $deleteExerciseDTO, int $id)
    {
        return match (ExercisesTypes::inEnum($deleteExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => Dictionary::whereId($id)->delete(),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)->delete(),
            default => false
        };
    }

    public function create(CreateExerciseDTO $createExerciseDTO): \Illuminate\Database\Eloquent\Model|Dictionary|bool|CompilePhrase
    {
        return match (ExercisesTypes::inEnum($createExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => Dictionary::create(['dictionary' => $createExerciseDTO->data]),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::create(['phrase' => $createExerciseDTO->data]),
            default => false
        };
    }
}
