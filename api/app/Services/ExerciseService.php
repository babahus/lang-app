<?php

namespace App\Services;

use App\Constants\PictureExerciseFilesPath;
use App\Enums\ExercisesTypes;
use Illuminate\Support\Facades\DB;
use App\Models\{PairExercise,
    PictureExercise,
    ProgressExercise,
    Sentence,
    User,
    Audit,
    Exercise,
    Dictionary,
    CompilePhrase,
    Stage,
    Course};
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
     * @var PairExerciseService
     */
    private PairExerciseService $pairExerciseService;
    /**
     * @var PictureExerciseService
     */
    private PictureExerciseService $pictureExerciseService;
    /**
     * @var SentenceExerciseService
     */
    private SentenceExerciseService $sentenceExerciseService;

    /**
     * @param AuditService $auditService
     * @param CompilePhraseService $compilePhrase
     * @param DictionaryService $dictionaryService
     * @param PairExerciseService $pairExerciseService
     * @param PictureExerciseService $pictureExerciseService
     * @param SentenceExerciseService $sentenceExerciseService
     */
    public function __construct (
        AuditService $auditService,
        CompilePhraseService $compilePhrase,
        DictionaryService $dictionaryService,
        PairExerciseService $pairExerciseService,
        PictureExerciseService $pictureExerciseService,
        SentenceExerciseService $sentenceExerciseService,
    ) {
        $this->compilePhrase = $compilePhrase;
        $this->auditService = $auditService;
        $this->dictionaryService = $dictionaryService;
        $this->pairExerciseService = $pairExerciseService;
        $this->pictureExerciseService = $pictureExerciseService;
        $this->sentenceExerciseService = $sentenceExerciseService;
    }

    /**
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getAllExercises(int $userId): \Illuminate\Database\Eloquent\Collection|array {
        return Exercise::where('account_id', $userId)
            ->whereNull('course_id')
            ->whereNull('stage_id')
            ->with(['dictionary', 'compilePhrase', 'audit', 'pairExercise', 'pictureExercise'])
            ->get();
    }

    /**
     * @param string $type
     * @param int $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|bool
     */
    public function getExercisesByType(string $type, int $userId): \Illuminate\Contracts\Pagination\LengthAwarePaginator|bool {
        return match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY       => Dictionary::paginate(10),
            ExercisesTypes::COMPILE_PHRASE   => CompilePhrase::paginate(10),
            ExercisesTypes::AUDIT            => Audit::paginate(10),
            ExercisesTypes::PAIR_EXERCISE    => PairExercise::paginate(10),
            ExercisesTypes::PICTURE_EXERCISE => PictureExercise::paginate(10),
            ExercisesTypes::SENTENCE         => Sentence::paginate(10),
            default => false
        };
    }

    /**
     * @param string $type
     * @param int $id
     * @param int $userId
     * @return CompilePhrase|Audit|Dictionary|PairExercise|PictureExercise|Sentence|bool
     */
    public function getExerciseByIdAndType(string $type, int $id, int $userId): CompilePhrase|Audit|Dictionary|PairExercise|PictureExercise|Sentence|bool {
        $exercise = match (ExercisesTypes::inEnum($type)) {
            ExercisesTypes::DICTIONARY       => Dictionary::whereId($id)->first(),
            ExercisesTypes::COMPILE_PHRASE   => CompilePhrase::whereId($id)->first(),
            ExercisesTypes::AUDIT            => Audit::whereId($id)->first(),
            ExercisesTypes::PAIR_EXERCISE    => PairExercise::whereId($id)->first(),
            ExercisesTypes::PICTURE_EXERCISE => PictureExercise::whereId($id)->first(),
            ExercisesTypes::SENTENCE         => Sentence::whereId($id)->first(),
            default => false
        };

        return $exercise ?: false;
    }

    /**
     * @param UpdateExerciseDTO $updateExerciseDTO
     * @param int $id
     * @return bool|int
     */
    public function update(UpdateExerciseDTO $updateExerciseDTO, int $id): bool|int
    {
        return match (ExercisesTypes::inEnum($updateExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => call_user_func(function () use ($id, $updateExerciseDTO): bool {
                $dictionary = Dictionary::whereId($id)->first();

                if ($dictionary !== null) {
                    return $this->dictionaryService->updateDictionary($dictionary, $updateExerciseDTO->data);
                } else {
                    return false;
                }
            }),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::whereId($id)
                ->update(['phrase' => $updateExerciseDTO->data]),
            ExercisesTypes::AUDIT => call_user_func(function () use ($id, $updateExerciseDTO): bool {
                $audit = Audit::findOrFail($id);
                $oldPath = $audit->path;

                $strPath = sprintf(AuditFilesPath::AUDIT_FILE_PATH, $audit->id, $audit->id, $updateExerciseDTO->data->getClientOriginalExtension());
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                Storage::disk('public')->put(
                    $strPath,
                    $updateExerciseDTO->data->getContent()
                );

                $audit->path = $strPath;
                $audit->transcription = $updateExerciseDTO->additional_data;
                $audit->save();

                return true;
            }),
            ExercisesTypes::PAIR_EXERCISE => $this->pairExerciseService->updatePairExercise($id, json_decode($updateExerciseDTO->data, true)),
            ExercisesTypes::PICTURE_EXERCISE => call_user_func(function () use ($id, $updateExerciseDTO): bool {
                $exercise = PictureExercise::findOrFail($id);
                $oldImagePath = $exercise->image_path;

                $strImagePath = sprintf(PictureExerciseFilesPath::PICTURE_EXERCISE_PATH, $exercise->id, $exercise->id, $updateExerciseDTO->data->getClientOriginalExtension());
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
                Storage::disk('public')->put(
                    $strImagePath,
                    $updateExerciseDTO->data->getContent()
                );

                $exercise->image_path = $strImagePath;
                $exercise->option_json = $updateExerciseDTO->additional_data;
                $exercise->save();

                return true;
            }),

            ExercisesTypes::SENTENCE => Sentence::whereId($id)
                ->update([
                    'sentence_with_gaps'   => $updateExerciseDTO->data,
                    'correct_answers_json' => $updateExerciseDTO->additional_data
                ]),
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
                ExercisesTypes::DICTIONARY       => $this->dictionaryService->deleteDictionary(Dictionary::whereId($id)->first(), $deleteExerciseDTO->data),
                ExercisesTypes::COMPILE_PHRASE   => CompilePhrase::whereId($id)->delete(),
                ExercisesTypes::AUDIT            => call_user_func(function() use ($id): bool {
                    $auditObj = Audit::whereId($id)->first();
                    Storage::disk('public')->deleteDirectory(sprintf(AuditFilesPath::DIRECTORY_PATH, $auditObj->id));

                    return $auditObj->delete();
                }),
                ExercisesTypes::PAIR_EXERCISE    => $this->pairExerciseService->deletePairExercise(PairExercise::whereId($id)->first()),
                ExercisesTypes::PICTURE_EXERCISE => call_user_func(function() use ($id): bool {
                    $pictureObj = PictureExercise::whereId($id)->first();
                    Storage::disk('public')->deleteDirectory(sprintf(PictureExerciseFilesPath::DIRECTORY_PATH, $pictureObj->id));

                    return $pictureObj->delete();
                }),
                ExercisesTypes::SENTENCE         => Sentence::whereId($id)->delete(),
                default => false
            };
        } catch (\Error $error)
        {

            return false;
        }
    }

    /**
     * @param CreateExerciseDTO $createExerciseDTO
     * @return Model|Audit|Dictionary|PairExercise|bool|CompilePhrase|Sentence|\Closure
     */
    public function create(CreateExerciseDTO $createExerciseDTO): \Illuminate\Database\Eloquent\Model|Audit|Dictionary|PairExercise|PictureExercise|Sentence|bool|CompilePhrase|\Closure {
        return match (ExercisesTypes::inEnum($createExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => Dictionary::create(['dictionary' => $createExerciseDTO->data]),
            ExercisesTypes::COMPILE_PHRASE => CompilePhrase::create(['phrase' => $createExerciseDTO->data]),
            ExercisesTypes::PAIR_EXERCISE => PairExercise::create(['correct_pair_json' => $createExerciseDTO->data]),
            ExercisesTypes::AUDIT => call_user_func(function() use ($createExerciseDTO): Audit
            {
                $auditObj = Audit::create();

                $strAuditPath = sprintf(AuditFilesPath::AUDIT_FILE_PATH, $auditObj->id, $auditObj->id, $createExerciseDTO->data->getClientOriginalExtension());
                Storage::disk('public')->put(
                    $strAuditPath,
                    $createExerciseDTO->data->getContent()
                );
                $auditObj->path = $strAuditPath;
                $auditObj->transcription = $createExerciseDTO->additional_data;
                $auditObj->save();

                return $auditObj;
            }),
            ExercisesTypes::PICTURE_EXERCISE => call_user_func(function () use ($createExerciseDTO): PictureExercise {
                $pictureObj = PictureExercise::create();

                $strImagePath = sprintf(PictureExerciseFilesPath::PICTURE_EXERCISE_PATH, $pictureObj->id, $pictureObj->id, $createExerciseDTO->data->getClientOriginalExtension());
                Storage::disk('public')->put(
                    $strImagePath,
                    $createExerciseDTO->data->getContent()
                );
                $pictureObj->image_path = $strImagePath;
                $pictureObj->option_json = $createExerciseDTO->additional_data;
                $pictureObj->save();

                return $pictureObj;
            }),
            ExercisesTypes::SENTENCE => Sentence::create([
                'sentence_with_gaps' => $createExerciseDTO->data,
                'correct_answers_json' => $createExerciseDTO->additional_data,
            ]),
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
     * @return Dictionary|CompilePhrase|Audit|PairExercise|PictureExercise|Sentence|bool
     */
    public function solving(SolvingExerciseDTO $solvingExerciseDTO): Dictionary|CompilePhrase|Audit|PairExercise|PictureExercise|Sentence|bool|string {
        $exercise = Exercise::where('id', $solvingExerciseDTO->exercise_id)
            ->where('exercise_type', $this->getClassType(ExercisesTypes::inEnum($solvingExerciseDTO->type)->value))
            ->where('exercise_id', $solvingExerciseDTO->id)
            ->first();

        $user = auth()->user();

        if ($exercise->course_id && !$user->courses()->where('course_id', $exercise->course_id)->exists()) {
            return 'You are not subscribed to this course';
        }

        if (!$exercise->course_id && !$user->exercises->where('id', $exercise->id)
                ->where('exercise_id', $exercise->exercise_id)
                ->whereNull('course_id')->count() > 0) {
            return 'You are not assigned an off-course exercise';
        }


        $progressExercise = $exercise->progressExercises()
            ->where('accounts_exercise_id', $exercise->id)
            ->where('account_id', $user->id)
            ->first();

        if ($progressExercise && ($progressExercise->solved == 1 || $progressExercise->account_id != $user->id)) {
            return 'Something went wrong';
        }

        return match (ExercisesTypes::inEnum($solvingExerciseDTO->type)) {
            ExercisesTypes::DICTIONARY => $this->dictionaryService->fillDictionary($solvingExerciseDTO),
            ExercisesTypes::COMPILE_PHRASE => $this->compilePhrase->solveCompilePhrase($solvingExerciseDTO, $exercise),
            ExercisesTypes::AUDIT => $this->auditService->solveAudit($solvingExerciseDTO, $exercise),
            ExercisesTypes::PAIR_EXERCISE => $this->pairExerciseService->solvePair($solvingExerciseDTO, $exercise),
            ExercisesTypes::PICTURE_EXERCISE => $this->pictureExerciseService->solvePicture($solvingExerciseDTO, $exercise),
            ExercisesTypes::SENTENCE => $this->sentenceExerciseService->solveSentence($solvingExerciseDTO, $exercise),
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
            ExercisesTypes::AUDIT   => Audit::class,
            ExercisesTypes::PAIR_EXERCISE => PairExercise::class,
            ExercisesTypes::PICTURE_EXERCISE => PictureExercise::class,
            ExercisesTypes::SENTENCE => Sentence::class
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
    public function checkIfExerciseIsAttached(int $exercise_id, int $account_id, string $type, ?int $stageId, ?int $courseId): bool
    {
        $query = Exercise::where('exercise_id', $exercise_id)
            ->where('account_id', $account_id)
            ->where('exercise_type', $type);

        if ($stageId !== null && $courseId !== null) {
            $query->where('stage_id', $stageId);
            $query->where('course_id', $courseId);
        } else {
            $query->whereNull('stage_id');
            $query->whereNull('course_id');
        }

        return $query->exists();
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

        if ($this->checkIfExerciseIsAttached(
            $moveUserExerciseDTO->id,
            $moveUserExerciseDTO->account_id,
            $typeClass,
            $moveUserExerciseDTO->stage_id,
            $moveUserExerciseDTO->course_id
        )) {
            return false;
        }

        Exercise::create([
            'exercise_id' => $moveUserExerciseDTO->id,
            'account_id' => $moveUserExerciseDTO->account_id,
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

        if (!$this->checkIfExerciseIsAttached(
            $moveUserExerciseDTO->id,
            $moveUserExerciseDTO->account_id,
            $typeClass,
            $moveUserExerciseDTO->stage_id,
            $moveUserExerciseDTO->course_id
        )) {
            return false;
        }

        $exercise = Exercise::whereExerciseId($moveUserExerciseDTO->id)
            ->where('account_id', $moveUserExerciseDTO->account_id)
            ->where('exercise_type', $typeClass)
            ->where('stage_id', $moveUserExerciseDTO->stage_id)
            ->where('course_id', $moveUserExerciseDTO->course_id)
            ->first();

        return $exercise->delete();
    }
}
