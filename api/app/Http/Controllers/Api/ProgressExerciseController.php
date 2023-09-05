<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressExercise\DeleteProgressRequest;
use App\Http\Resources\ProgressExerciseResource;
use App\Http\Response\ApiResponse;
use App\Services\ProgressExerciseService;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProgressExerciseController extends Controller
{
    protected $progressExerciseService;

    public function __construct(ProgressExerciseService $progressExerciseService)
    {
        $this->progressExerciseService = $progressExerciseService;
    }

    public function getUserCompletedExercises(int $user_id)
    {
        $progressExercise = $this->progressExerciseService->getUserCompletedExercises($user_id);

        if (!$progressExercise){

            return new ApiResponse('Unable to find completed exercises for the user', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse(ProgressExerciseResource::collection($progressExercise));
    }

    public function deleteUserProgress(DeleteProgressRequest $deleteProgressRequest)
    {
        $deleteProgress =  $this->progressExerciseService->deleteUserProgress($deleteProgressRequest->getDTO());

        if (!$deleteProgress){

            return new ApiResponse('Failed to delete exercise progress for user', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse('Result of the exercise was successfully deleted');
    }

    public function getProgressByStage($userId, $stageId)
    {
        $progressByStage = $this->progressExerciseService->getProgressByStage($userId, $stageId);

        if (is_string($progressByStage)){

            return new ApiResponse($progressByStage, ResponseAlias::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse($progressByStage);
    }
}
