<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgressExercise\DeleteProgressRequest;
use App\Http\Resources\ProgressExerciseResource;
use App\Http\Response\ApiResponse;
use App\Models\Course;
use App\Models\Stage;
use App\Models\User;
use App\Services\ProgressExerciseService;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProgressExerciseController extends Controller
{
    protected ProgressExerciseService $progressExerciseService;

    public function __construct(ProgressExerciseService $progressExerciseService)
    {
        $this->progressExerciseService = $progressExerciseService;
    }

    public function getUserCompletedExercises(int $user_id): ApiResponse
    {
        $progressExercise = $this->progressExerciseService->getUserCompletedExercises($user_id);

        if (!$progressExercise){

            return new ApiResponse('Unable to find completed exercises for the user', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse(ProgressExerciseResource::collection($progressExercise));
    }

    public function deleteUserProgress(DeleteProgressRequest $deleteProgressRequest): ApiResponse
    {
        $deleteProgress =  $this->progressExerciseService->deleteUserProgress($deleteProgressRequest->getDTO());

        if (!$deleteProgress){

            return new ApiResponse('Failed to delete exercise progress for user', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse('Result of the exercise was successfully deleted');
    }

    public function getProgressByStage($userId, $stageId): ApiResponse
    {
        $progressByStage = $this->progressExerciseService->getProgressByStage($userId, $stageId);

        if (is_string($progressByStage)){

            return new ApiResponse($progressByStage, ResponseAlias::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse($progressByStage);
    }

    public function getCourseProgressForCurrentUser(Course $course): ApiResponse
    {
        $progressCourse = $this->progressExerciseService->getCourseProgressForCurrentUser($course);

        if (is_string($progressCourse)){

            return new ApiResponse($progressCourse, ResponseAlias::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse($progressCourse);
    }

    public function getCountProgressOfAllExercisesForUser(User $user): ApiResponse
    {
        $exerciseProgressCountArr = $this->progressExerciseService->getCountProgressOfAllExercisesForUser($user);

        return new ApiResponse($exerciseProgressCountArr);
    }

    public function canCurrentUserProceedToNextStage(Stage $stage): ApiResponse
    {
        $user = auth()->user();
        $canProceed = $this->progressExerciseService->canUserProceedToNextStage($user, $stage);

        if (!$canProceed){
          return new ApiResponse($canProceed, ResponseAlias::HTTP_BAD_REQUEST, false);
        };

        return new ApiResponse($canProceed);
    }

}
