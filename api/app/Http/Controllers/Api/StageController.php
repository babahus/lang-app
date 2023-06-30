<?php

namespace App\Http\Controllers;

use App\Http\Requests\StageCreateRequest;
use App\Http\Requests\StageUpdateRequest;
use App\Http\Resources\StageResource;
use App\Services\StageService;
use Illuminate\Http\Request;
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
    public function index() {
        $stages = $this->stageService->getAllStages();

        return new ApiResponse(StageResource::collection($stages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StageCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StageCreateRequest $request) {
        $stage = $this->stageService->createStage($request->validated());

        return new ApiResponse(StageResource::make($stage));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
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
    public function update(StageUpdateRequest $request, $id) {
        $stage = $this->stageService->updateStage($id, $request->validated());

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
    public function destroy($id) {
        $success = $this->stageService->deleteStage($id);

        if (!$success) {
            return new ApiResponse('Stage not found', Response::HTTP_NOT_FOUND, false);
        }

        return new ApiResponse('Stage deleted successfully', Response::HTTP_OK);
    }
}
