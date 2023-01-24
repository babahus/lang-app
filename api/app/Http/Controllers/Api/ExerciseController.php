<?php

namespace App\Http\Controllers\Api;

use App\Enums\ExercisesTypes;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompilePhraseResource;
use App\Http\Resources\DictionaryResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Response\ApiResponse;
use App\Models\CompilePhrase;
use App\Models\Dictionary;
use App\Models\Exercise;
use App\Models\User;
use App\Services\ExerciseService;
use Illuminate\Http\Response;

class ExerciseController extends Controller
{
    private ExerciseService $exerciseService;

    public function __construct(ExerciseService $exerciseService){
        $this->exerciseService = $exerciseService;
    }

    public function getAllExercises(): ApiResponse
    {
        $userExercises = $this->exerciseService->getAllExercises(auth()->id());

        return new ApiResponse(ExerciseResource::collection($userExercises));
    }

    public function getExerciseByIdAndType(string $type, int $id): ApiResponse
    {
        $exercise = $this->exerciseService->getExerciseByIdAndType($type, $id, auth()->id());
        if (!$exercise){
            return new ApiResponse('Can not find Exercises by Id', Response::HTTP_BAD_REQUEST, false);
        }
        return match (ExercisesTypes::tryFrom($type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::make($exercise)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::make($exercise))
        };
    }

    public function getExercisesByType(string $type): ApiResponse
    {
        $exercises = $this->exerciseService->getExercisesByType($type, auth()->id());
        if (!$exercises){
            return new ApiResponse('Can not find Exercises Type', Response::HTTP_BAD_REQUEST, false);
        }
        return match (ExercisesTypes::tryFrom($type)){
            ExercisesTypes::DICTIONARY     => new ApiResponse(DictionaryResource::collection($exercises)),
            ExercisesTypes::COMPILE_PHRASE => new ApiResponse(CompilePhraseResource::collection($exercises))
        };
    }
}
