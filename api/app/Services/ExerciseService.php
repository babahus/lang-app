<?php

namespace App\Services;

use App\Enums\ExercisesTypes;
use App\Models\{
    User,
    Audit,
    Exercise,
    Dictionary,
    CompilePhrase,
    Stage,
    Course,
};
use App\DataTransfers\{
    CreateExerciseDTO,
    DeleteExerciseDTO,
    MoveUserExerciseDTO,
    SolvingExerciseDTO,
    UpdateExerciseDTO,
};
use App\Constants\AuditFilesPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Contracts\ExerciseServiceContract;

final class ExerciseService implements ExerciseServiceContract {
    /**
     * @var AuditService
     */
    private AuditService $auditService;
    /**
     * @var CompilePhraseService
     */
    private CompilePhraseService $compilePhrase;
    /**
     * @var DictionaryService
     */
    private DictionaryService $dictionaryService;

    /**
     * @param AuditService $auditService
     * @param CompilePhraseService $compilePhrase
     * @param DictionaryService $dictionaryService
     */
    public function __construct (
        AuditService $auditService,
        CompilePhraseService $compilePhrase,
        DictionaryService $dictionaryService
    ) {
        $this->compilePhrase = $compilePhrase;
        $this->auditService = $auditService;
        $this->dictionaryService = $dictionaryService;
    }

    /**
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getAllExercises(int $userId): \Illuminate\Database\Eloquent\Collection|array {
        return User::with(['dictionary', 'compilePhrase', 'audit'])->where('id', $userId)->get();
    }

    /**
     * @param string $type
     * @param int $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|bool
     */
    public function getExercisesByType(string $type, int $userId): \Illuminate\Contracts\Pagination\LengthAwarePaginator|bool {
        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::paginate(10),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::paginate(10),
            ExercisesTypes::AUDIT => Audit::paginate(10),
            default => false
        };
    }

    /**
     * @param string $type
     * @param int $id
     * @param int $userId
     * @return CompilePhrase|Audit|Dictionary|bool
     */
    public function getExerciseByIdAndType(string $type, int $id, int $userId): CompilePhrase|Audit|Dictionary|bool {
        $exercise = match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::whereId($id)->first(),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)->first(),
            ExercisesTypes::AUDIT => Audit::whereId($id)->first(),
            default => false
        };

        return $exercise ?: false;
    }

    /**
     * @param UpdateExerciseDTO $updateExerciseDTO
     * @param int $id
     * @return bool|int
     */
    public function update(UpdateExerciseDTO $updateExerciseDTO, int $id): bool|int {
        return match (ExercisesTypes::inEnum($updateExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => call_user_func(function() use ($id, $updateExerciseDTO): bool {
                $dictionary = Dictionary::whereId($id)->first();

                if ($dictionary !== null) {
                    return $this->dictionaryService->updateDictionary($dictionary, $updateExerciseDTO->data);
                } else {
                    return false;
                }
            }),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)
                     ->update(['phrase' => $updateExerciseDTO->data]),
            ExercisesTypes::AUDIT => Audit::whereId($id)
                     ->update(['transcript' => $updateExerciseDTO->data]),
            default => false
        };
    }

    /**
     * @param DeleteExerciseDTO $deleteExerciseDTO
     * @param int $id
     * @return bool|null
     */
    public function delete(DeleteExerciseDTO $deleteExerciseDTO, int $id) : bool|null {
        if (Exercise::whereExerciseId($id)->where('account_id', auth()->id())->get()->isNotEmpty() && $deleteExerciseDTO->type !== "dictionary"){
            \Auth::user()->exercises()->detach($id, ['exercise_type' => $deleteExerciseDTO->type]);
        }

        try {
            return match (ExercisesTypes::inEnum($deleteExerciseDTO->type)) {
                ExercisesTypes::DICTIONARY     => $this->dictionaryService->deleteDictionary(Dictionary::whereId($id)->first(), $deleteExerciseDTO->data),
                ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)->delete(),
                ExercisesTypes::AUDIT          => call_user_func(function() use ($id): bool {
                    $auditObj = Audit::whereId($id)->first();
                    Storage::disk('public')->deleteDirectory(sprintf(AuditFilesPath::DIRECTORY_PATH, $auditObj->id));

                    return $auditObj->delete();
                }),
                default => false
            };
        } catch (\Error $error)
        {

            return false;
        }
    }

    /**
     * @param CreateExerciseDTO $createExerciseDTO
     * @return Model|Audit|Dictionary|bool|CompilePhrase|\Closure
     */
    public function create(CreateExerciseDTO $createExerciseDTO): \Illuminate\Database\Eloquent\Model|Audit|Dictionary|bool|CompilePhrase|\Closure {
        return match (ExercisesTypes::inEnum($createExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => Dictionary::create(['dictionary' => $createExerciseDTO->data]),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::createIfNotExist($createExerciseDTO->data),
            ExercisesTypes::AUDIT => call_user_func(function() use ($createExerciseDTO): Audit
            {
                $auditObj = Audit::create();
                $strAuditPath = sprintf(AuditFilesPath::AUDIT_FILE_PATH, $auditObj->id, $auditObj->id, $createExerciseDTO->data->getClientOriginalExtension());
                Storage::disk('public')->put(
                    $strAuditPath,
                    $createExerciseDTO->data->getContent()
                );
                $auditObj->path = $strAuditPath;
                $auditObj->transcription = $createExerciseDTO->transcript;
                $auditObj->save();

                return $auditObj;
            }),
            default => false
        };
    }

    /**
     * @param MoveUserExerciseDTO $moveUserExerciseDTO
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @return bool
     */
//    public function attach(MoveUserExerciseDTO $moveUserExerciseDTO, User|\Illuminate\Contracts\Auth\Authenticatable $user): bool {
//        $typeClass = $this->getClassType($moveUserExerciseDTO->type);
//
//        if (!$this->checkIfExerciseIsAttached($moveUserExerciseDTO->id, $user->id, $typeClass)){
//            return false;
//        }
//        if ($typeClass == Dictionary::class && !is_null($this->checkIfDictionaryIsAttached($moveUserExerciseDTO->id))){
//
//            return false;
//        }
//        $user->exercises()->attach($moveUserExerciseDTO->id, ['exercise_type' => $typeClass]);
//
//        return true;
//    }

    /**
     * @param MoveUserExerciseDTO $moveUserExerciseDTO
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @return bool
     */
    public function detach(MoveUserExerciseDTO $moveUserExerciseDTO, User|\Illuminate\Contracts\Auth\Authenticatable $user): bool {
        $typeClass = $this->getClassType($moveUserExerciseDTO->type);

        $user->exercises()->detach($moveUserExerciseDTO->id, ['exercise_type' => $typeClass]);

        return true;
    }

    /**
     * @param SolvingExerciseDTO $solvingExerciseDTO
     * @return Dictionary|CompilePhrase|Audit|bool
     */
    public function solving(SolvingExerciseDTO $solvingExerciseDTO): Dictionary|CompilePhrase|Audit|bool|string {
        $exercise = Exercise::where('account_id', auth()->id())
            ->where('exercise_id', $solvingExerciseDTO->id)
            ->where('exercise_type', '=', $this->getClassType(ExercisesTypes::inEnum($solvingExerciseDTO->type)->value))
            ->first();

        if ((!$exercise
            || $exercise->solved == 1
            || !($exercise->account_id == auth()->id()))
        ) {
            return 'Something went wrong';
        }

        return match (ExercisesTypes::inEnum($solvingExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => $this->dictionaryService->fillDictionary($solvingExerciseDTO),
            ExercisesTypes::COMPILE_PHRASE => $this->compilePhrase->solveCompilePhrase($solvingExerciseDTO),
            ExercisesTypes::AUDIT => $this->auditService->solveAudit($solvingExerciseDTO),
            default => 'Something went wrong'
        };
    }

    /**
     * @param string $type
     * @return string
     */
    public function getClassType(string $type): string {

        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY => Dictionary::class,
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::class,
            ExercisesTypes::AUDIT => Audit::class
        };
    }

    /**
     * @param int $exercise_id
     * @param int $user_id
     * @param string $type
     * @param int $stageId
     * @param int $courseId
     * @return bool
     */
    public function checkIfExerciseIsAttached(int $exercise_id, int $user_id, string $type, int $stageId, int $courseId): bool {

        $exercise = Exercise::whereExerciseId($exercise_id)
            ->where('account_id', '=', $user_id)
            ->where('exercise_type', '=', $type)
            ->where('stage_id', $stageId)
            ->where('course_id', $courseId)
            ->first();

        if ($exercise)
        {
            return false;
        }

        return true;
    }

    /**
     * @param int $exerciseId
     * @return Model|\Illuminate\Database\Eloquent\Builder|null
     */
    public function checkIfDictionaryIsAttached(int $exerciseId): Model|\Illuminate\Database\Eloquent\Builder|null {

        return Exercise::whereExerciseId($exerciseId)
            ->whereType($this->getClassType('dictionary'))
            ->first();
    }

    /**
     * @param MoveUserExerciseDTO $moveUserExerciseDTO
     * @param int $stageId
     * @param int $courseId
     * @return bool
     */
    public function attachExerciseToStageCourse(MoveUserExerciseDTO $moveUserExerciseDTO): bool {
        $typeClass = $this->getClassType($moveUserExerciseDTO->type);

        if (!$this->checkIfExerciseIsAttached($moveUserExerciseDTO->id, auth()->user()->id, $typeClass, $moveUserExerciseDTO->stage_id, $moveUserExerciseDTO->course_id)) {
            return false;
        }

        $user = auth()->user();

        $user->exercises()->attach($moveUserExerciseDTO->id, [
            'exercise_type' => $typeClass,
            'stage_id' => $moveUserExerciseDTO->stage_id,
            'course_id' => $moveUserExerciseDTO->course_id,
        ]);

        return true;
    }

    /**
     * @param MoveUserExerciseDTO $moveUserExerciseDTO
     * @param int $stageId
     * @param int $courseId
     * @return bool
     */
    public function detachExerciseToStageCourse(MoveUserExerciseDTO $moveUserExerciseDTO): bool {
        $typeClass = $this->getClassType($moveUserExerciseDTO->type);

        if ($this->checkIfExerciseIsAttached($moveUserExerciseDTO->id, auth()->user()->id, $typeClass, $moveUserExerciseDTO->stage_id, $moveUserExerciseDTO->course_id)) {
            return false;
        }

        Exercise::whereExerciseId($moveUserExerciseDTO->id)
            ->where('account_id', auth()->user()->id)
            ->where('exercise_type', $typeClass)
            ->where('stage_id', $moveUserExerciseDTO->stage_id)
            ->where('course_id', $moveUserExerciseDTO->course_id)
            ->delete();

        return true;
    }
}
