<?php

namespace App\Http\Controllers\Api;

use App\DataTransfers\MoveUserExerciseDTO;
use Illuminate\Http\Response;
use App\Enums\ExercisesTypes;
use App\Http\Requests\{
    CreateExerciseRequest,
    DeleteExerciseRequest,
    MoveUserExerciseRequest,
    SolvingExerciseRequest,
    UpdateExerciseRequest,
};
use App\Http\Resources\{AuditResource,
    CompilePhraseResource,
    DictionaryResource,
    ExerciseResource,
    PairExerciseResource};
use App\Services\{
    AuditApiService,
    ExerciseService
};
use App\Http\Response\ApiResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class ExerciseController extends Controller {
    /**
     * @var ExerciseService
     */
    private ExerciseService $exerciseService;
    /**
     * @var AuditApiService
     */
    private AuditApiService $auditService;

    /**
     * @param ExerciseService $exerciseService
     * @param AuditApiService $auditService
     */
    public function __construct(
        ExerciseService $exerciseService,
        AuditApiService $auditService
    ){
        $this->exerciseService = $exerciseService;
        $this->auditService = $auditService;
    }

    /**
     * @return ApiResponse
     */
    public function index(): ApiResponse {
        $userExercises = $this->exerciseService->getAllExercises(auth()->id());

        return new ApiResponse(ExerciseResource::collection($userExercises));
    }

    /**
     * @param string $type
     * @param int $id
     * @return ApiResponse
     */
    public function show(string $type, int $id): ApiResponse {
        $exercise = $this->exerciseService->getExerciseByIdAndType($type, $id, auth()->id());

        if (!$exercise){

            return new ApiResponse('Can not find Exercises by Id', Response::HTTP_BAD_REQUEST, false);
        }

        return match (ExercisesTypes::inEnum($type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::make($exercise)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::make($exercise)),
            ExercisesTypes::AUDIT          => new ApiResponse(AuditResource::make($exercise)),
            ExercisesTypes::PAIR_EXERCISE  => new ApiResponse(PairExerciseResource::make($exercise)),
        };
    }

    /**
     * @param string $type
     * @return ApiResponse
     */
    public function getExercisesByType(string $type): ApiResponse {
        $exercises = $this->exerciseService->getExercisesByType($type, auth()->id());

        if (!$exercises){

            return new ApiResponse('Can not find Exercises Type', Response::HTTP_BAD_REQUEST, false);
        }

        return match (ExercisesTypes::inEnum($type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::collection($exercises)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::collection($exercises)),
            ExercisesTypes::AUDIT          => new ApiResponse(AuditResource::collection($exercises)),
            ExercisesTypes::PAIR_EXERCISE  => new ApiResponse(PairExerciseResource::collection($exercises)),
        };
    }

    /**
     * @param UpdateExerciseRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function update(UpdateExerciseRequest $request, int $id): ApiResponse {
        $isUpdated = $this->exerciseService->update($request->getDTO(), $id);

        if (!$isUpdated){

            return new ApiResponse('Something went wrong', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse('Exercise is successfully updated');
    }

    /**
     * @param DeleteExerciseRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(DeleteExerciseRequest $request, int $id): ApiResponse {
        $isDeleted = $this->exerciseService->delete($request->getDTO(), $id);

        if (!$isDeleted){

            return new ApiResponse('Something went wrong', ResponseAlias::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse('Exercise is successfully deleted');
    }

    /**
     * @param CreateExerciseRequest $request
     * @return ApiResponse
     */
    public function store(CreateExerciseRequest $request): ApiResponse {
        $createdExercise = $this->exerciseService->create($request->getDTO());

        return match (ExercisesTypes::inEnum($request->getDTO()->type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::make($createdExercise)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::make($createdExercise)),
            ExercisesTypes::AUDIT          => new ApiResponse(AuditResource::make($createdExercise)),
            ExercisesTypes::PAIR_EXERCISE  => new ApiResponse(PairExerciseResource::make($createdExercise)),
        };
    }

    /**
     * @param MoveUserExerciseRequest $request
     * @return ApiResponse
     */
    public function attachExerciseToStageCourses(MoveUserExerciseRequest $request): ApiResponse {

        if ($this->exerciseService->attachExerciseToStageCourse($request->getDTO())) {
            return new ApiResponse('Successful attached exercise to stage and course');
        }

        return new ApiResponse('Something went wrong', ResponseAlias::HTTP_BAD_REQUEST, false);
    }

    /**
     * @param MoveUserExerciseRequest $request
     * @return ApiResponse
     */
    public function detachExerciseToStageCourses(MoveUserExerciseRequest $request): ApiResponse {

        if ($this->exerciseService->detachExerciseToStageCourse($request->getDTO())) {
            return new ApiResponse('Successful detached exercise from stage and course');
        }

        return new ApiResponse('Something went wrong', ResponseAlias::HTTP_BAD_REQUEST, false);
    }

    /**
     * @param SolvingExerciseRequest $request
     * @return ApiResponse|string
     */
    public function solving(SolvingExerciseRequest $request): ApiResponse|string {
        $solved = $this->exerciseService->solving($request->getDTO());

        if (!is_string($solved)){

            return match (ExercisesTypes::inEnum($request->getDTO()->type)){
                ExercisesTypes::DICTIONARY                            => new ApiResponse(DictionaryResource::make($solved)),
                ExercisesTypes::COMPILE_PHRASE, ExercisesTypes::AUDIT => new ApiResponse('Successfully solved exercise'),
                ExercisesTypes::PAIR_EXERCISE                         => new ApiResponse(PairExerciseResource::make($solved)),
            };
        }

        return new ApiResponse($solved, ResponseAlias::HTTP_BAD_REQUEST, false);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadAudioAndTranscript(int $id) {
        $upload_url = $this->auditService->uploadAudio($id);

        return $this->auditService->transcriptAudio($upload_url, $id);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function webHook(\Illuminate\Http\Request $request): bool {
        $this->auditService->getResult($request->only(['transcript_id', 'status', 'text']));

        return true;
    }
}
