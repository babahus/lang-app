<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Stages\StageCreateRequest;
use App\Http\Requests\Stages\StageUpdateRequest;
use App\Http\Resources\StageResource;
use App\Services\StageService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Response\ApiResponse;

class StageController extends Controller {
    private StageService $stageService;

    public function __construct(StageService $stageService) {
        $this->stageService = $stageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): ApiResponse {
        $stages = $this->stageService->getAllStages();

        return new ApiResponse(StageResource::collection($stages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StageCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StageCreateRequest $request): ApiResponse {
        $stage = $this->stageService->createStage($request->getDTO());

        return new ApiResponse(StageResource::make($stage));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): ApiResponse {
        $stage = $this->stageService->getStageById($id);

        if (!$stage) {
            return new ApiResponse('Stage not found', Response::HTTP_NOT_FOUND, false);
        }

        return new ApiResponse(StageResource::make($stage));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StageUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StageUpdateRequest $request, int $id): ApiResponse {
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): ApiResponse {
        $success = $this->stageService->deleteStage($id);

        if (!$success) {
            return new ApiResponse('Stage not found', Response::HTTP_NOT_FOUND, false);
        }

        return new ApiResponse('Stage deleted successfully', Response::HTTP_OK);
    }
}
