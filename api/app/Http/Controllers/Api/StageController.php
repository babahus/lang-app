<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stages\StageCreateRequest;
use App\Http\Requests\Stages\StageUpdateRequest;
use App\Http\Requests\Stages\StageDeleteRequest;
use App\Http\Requests\Stages\CourseIdRequest;
use App\Http\Resources\StageResource;
use App\Services\StageService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Response\ApiResponse;

class StageController extends Controller
{
    private StageService $stageService;

    public function __construct(StageService $stageService)
    {
        $this->stageService = $stageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Stages\CourseIdRequest  $request
     * @param  int  $id
     * @return \App\Http\Response\ApiResponse
     */
    public function index(CourseIdRequest $request, int $id): ApiResponse
    {
        $stages = $this->stageService->getAllStages($id);

        return new ApiResponse(StageResource::collection($stages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Stages\StageCreateRequest  $request
     * @return \App\Http\Response\ApiResponse
     */
    public function store(StageCreateRequest $request): ApiResponse
    {
        $stage = $this->stageService->createStage($request->getDTO());

        return new ApiResponse(StageResource::make($stage));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Response\ApiResponse
     */
    public function show(int $id): ApiResponse
    {
        $stage = $this->stageService->getStageById($id);

        if (!$stage) {
            return new ApiResponse('Stage not found', Response::HTTP_NOT_FOUND, false);
        }

        $foundStage = $stage->find($id);

        if (!$foundStage) {
            return new ApiResponse('Stage not found', Response::HTTP_NOT_FOUND, false);
        }

        return new ApiResponse(StageResource::make($foundStage));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Stages\StageUpdateRequest  $request
     * @param  int  $id
     * @return \App\Http\Response\ApiResponse
     */
    public function update(StageUpdateRequest $request, int $id): ApiResponse
    {
        $stage = $this->stageService->updateStage($id, $request->getDTO());

        if (!$stage) {
            return new ApiResponse('Stage not found', Response::HTTP_NOT_FOUND, false);
        }

        return new ApiResponse(StageResource::make($stage));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  \App\Http\Requests\Stages\StageDeleteRequest  $request
     * @return \App\Http\Response\ApiResponse
     */
    public function destroy(StageDeleteRequest $request, int $id): ApiResponse
    {
        $response = $this->stageService->deleteStageById($id);

        return new ApiResponse('Stage deleted', Response::HTTP_OK);
    }
}
