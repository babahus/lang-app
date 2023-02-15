<?php

namespace App\Services;

use App\Constants\AuditFilesPath;
use App\Contracts\ExerciseServiceContract;
use App\DataTransfers\CreateExerciseDTO;
use App\DataTransfers\DeleteExerciseDTO;
use App\DataTransfers\MoveUserExerciseDTO;
use App\DataTransfers\UpdateExerciseDTO;
use App\Enums\ExercisesTypes;
use App\Models\Audit;
use App\Models\CompilePhrase;
use App\Models\Dictionary;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ExerciseService implements ExerciseServiceContract
{
    public function getAllExercises(int $userId): \Illuminate\Database\Eloquent\Collection|array
    {
        return User::with(['dictionary', 'compilePhrase', 'audit'])->where('id', $userId)->get();
    }

    public function getExercisesByType(string $type, int $userId): \Illuminate\Contracts\Pagination\LengthAwarePaginator|bool
    {
        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::with('exercises')
                ->whereRelation('exercises',['user_id' => $userId])
                ->paginate(10),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::paginate(10),
            ExercisesTypes::AUDIT => Audit::paginate(10),
            default => false
        };
    }

    public function getExerciseByIdAndType(string $type, int $id, int $userId): CompilePhrase|Audit|Dictionary|bool
    {
        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::with('exercises')
                ->whereRelation('exercises',['user_id' => $userId, 'exercise_id' => $id])
                ->first(),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)->first(),
            ExercisesTypes::AUDIT => Audit::whereId($id)->first(),
            default => false
        };
    }

    public function update(UpdateExerciseDTO $updateExerciseDTO, int $id): bool|int
    {
        return match (ExercisesTypes::inEnum($updateExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => Dictionary::whereId($id)
                     ->update(['dictionary' => $updateExerciseDTO->data]),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)
                     ->update(['phrase' => $updateExerciseDTO->data]),
            ExercisesTypes::AUDIT => Audit::whereId($id)
                ->update(['transcript' => $updateExerciseDTO->data]),
            default => false
        };
    }

    public function delete(DeleteExerciseDTO $deleteExerciseDTO, int $id)
    {
        if (Exercise::whereExerciseId($id)->where('user_id', auth()->id())->get()->isNotEmpty()){
            \Auth::user()->exercises()->detach($id, ['type' => $deleteExerciseDTO->type]);
        }

        return match (ExercisesTypes::inEnum($deleteExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY     => Dictionary::whereId($id)->delete(),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)->delete(),
            ExercisesTypes::AUDIT          => call_user_func(function() use ($id): bool {
                $auditObj = Audit::whereId($id)->first();

                Storage::disk('public')->deleteDirectory(sprintf(AuditFilesPath::DIRECTORY_PATH, $auditObj->id));
                return $auditObj->delete();
            }),
            default => false
        };
    }

    public function create(CreateExerciseDTO $createExerciseDTO): \Illuminate\Database\Eloquent\Model|Audit|Dictionary|bool|CompilePhrase|\Closure
    {
        return match (ExercisesTypes::inEnum($createExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => Dictionary::create(['dictionary' => $createExerciseDTO->data]),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::create(['phrase' => $createExerciseDTO->data]),
            ExercisesTypes::AUDIT => call_user_func(function() use ($createExerciseDTO): Audit
            {
                $auditObj = Audit::create();
                $strAuditPath = sprintf(AuditFilesPath::AUDIT_FILE_PATH, $auditObj->id, $auditObj->id, $createExerciseDTO->data->getClientOriginalExtension());
                Storage::disk('public')->put(
                    $strAuditPath,
                    $createExerciseDTO->data->getContent()
                );
                $auditObj->path = $strAuditPath;
                $auditObj->save();
                return $auditObj;
            }),
            default => false
        };
    }

    public function attach(MoveUserExerciseDTO $moveUserExerciseDTO, User|\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {
        $typeClass = $this->getClassType($moveUserExerciseDTO->type);

        $user->exercises()->attach($moveUserExerciseDTO->id, ['type'=>$typeClass]);
        return true;
    }

    public function detach(MoveUserExerciseDTO $moveUserExerciseDTO, User|\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {
        $typeClass = $this->getClassType($moveUserExerciseDTO->type);

        $user->exercises()->detach($moveUserExerciseDTO->id, ['type'=>$typeClass]);
        return true;
    }

    public function getClassType(string $type): string
    {
        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::class,
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::class,
            ExercisesTypes::AUDIT => Audit::class
        };
    }
}
