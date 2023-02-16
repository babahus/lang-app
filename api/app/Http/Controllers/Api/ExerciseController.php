<?php

namespace App\Http\Controllers\Api;

use App\Enums\ExercisesTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExerciseRequest;
use App\Http\Requests\DeleteExerciseRequest;
use App\Http\Requests\MoveUserExerciseRequest;
use App\Http\Requests\SolvingExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Resources\AuditResource;
use App\Http\Resources\CompilePhraseResource;
use App\Http\Resources\DictionaryResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Response\ApiResponse;
use App\Services\AuditApiService;
use App\Services\ExerciseService;
use Illuminate\Http\Response;

class ExerciseController extends Controller
{
    private ExerciseService $exerciseService;
    private AuditApiService $auditService;

    public function __construct(
        ExerciseService $exerciseService,
        AuditApiService $auditService
    ){
        $this->exerciseService = $exerciseService;
        $this->auditService = $auditService;
    }

    public function index(): ApiResponse
    {
        $userExercises = $this->exerciseService->getAllExercises(auth()->id());

        return new ApiResponse(ExerciseResource::collection($userExercises));
    }

    public function show(string $type, int $id): ApiResponse
    {
        $exercise = $this->exerciseService->getExerciseByIdAndType($type, $id, auth()->id());
        if (!$exercise){
            return new ApiResponse('Can not find Exercises by Id', Response::HTTP_BAD_REQUEST, false);
        }
        return match (ExercisesTypes::inEnum($type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::make($exercise)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::make($exercise)),
            ExercisesTypes::AUDIT          => new ApiResponse(AuditResource::make($exercise)),
        };
    }

    public function getExercisesByType(string $type): ApiResponse
    {
        $exercises = $this->exerciseService->getExercisesByType($type, auth()->id());
        if (!$exercises){
            return new ApiResponse('Can not find Exercises Type', Response::HTTP_BAD_REQUEST, false);
        }
        return match (ExercisesTypes::inEnum($type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::collection($exercises)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::collection($exercises)),
            ExercisesTypes::AUDIT          => new ApiResponse(AuditResource::collection($exercises)),
        };
    }

    public function update(UpdateExerciseRequest $request, int $id): ApiResponse
    {
        $isUpdated = $this->exerciseService->update($request->getDTO(), $id);
        if (!$isUpdated){
            return new ApiResponse('Something went wrong', Response::HTTP_BAD_REQUEST, false);
        }
        return new ApiResponse('Exercise is successfully updated');
    }

    public function destroy(DeleteExerciseRequest $request, int $id): ApiResponse
    {
        $isDeleted = $this->exerciseService->delete($request->getDTO(), $id);
        if (!$isDeleted){
            return new ApiResponse('Something went wrong', Response::HTTP_BAD_REQUEST, false);
        }
        return new ApiResponse('Exercise is successfully deleted');
    }

    public function store(CreateExerciseRequest $request): ApiResponse
    {
        $createdExercise = $this->exerciseService->create($request->getDTO());
        return match (ExercisesTypes::inEnum($request->getDTO()->type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::make($createdExercise)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::make($createdExercise)),
            ExercisesTypes::AUDIT          => new ApiResponse(AuditResource::make($createdExercise)),
        };
    }

    public function attach(MoveUserExerciseRequest $request): ApiResponse
    {
        if ($this->exerciseService->attach($request->getDTO(), auth()->user())){
            return new ApiResponse('Successful attached exercise to user');
        }

        return new ApiResponse('Something went wrong', Response::HTTP_BAD_REQUEST, false);
    }

    public function detach(MoveUserExerciseRequest $request): ApiResponse
    {
        if ($this->exerciseService->detach($request->getDTO(), auth()->user())){
            return new ApiResponse('Successful detached exercise to user');
        }

        return new ApiResponse('Something went wrong', Response::HTTP_BAD_REQUEST, false);
    }

    public function solving(SolvingExerciseRequest $request): ApiResponse|string
    {
        $solved = $this->exerciseService->solving($request->getDTO());
        if ($solved){
            return match (ExercisesTypes::inEnum($request->getDTO()->type)){
                ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::make($solved)),
                ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::make($solved)),
                ExercisesTypes::AUDIT          => new ApiResponse(AuditResource::make($solved)),
            };
        }
        return new ApiResponse('Something went wrong', Response::HTTP_BAD_REQUEST, false);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadAudioAndTranscript(int $id)
    {
        $upload_url = $this->auditService->uploadAudio($id);
        return $this->auditService->transcriptAudio($upload_url, $id);
    }

    public function webHook(\Illuminate\Http\Request $request): bool
    {
        $this->auditService->getResult($request->only(['transcript_id', 'status', 'text']));
        return true;
    }
}
