<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\CourseCreateRequest;
use App\Http\Requests\Courses\CourseDeleteRequest;
use App\Http\Requests\Courses\CourseUpdateRequest;
use App\Http\Resources\CourseResource;
use App\Http\Response\ApiResponse;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CourseController extends Controller {
    private CourseService $courseService;

    public function __construct(CourseService $courseService) {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int|null $count
     * @return ApiResponse
     */
    public function index(?int $count = 10): ApiResponse {
        $courses = Course::paginate($count);

        return new ApiResponse(CourseResource::collection($courses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourseCreateRequest $request
     * @return ApiResponse
     */
    public function store(CourseCreateRequest $request): ApiResponse {
        $createdCourse = $this->courseService->create($request->getDTO());

        return new ApiResponse(CourseResource::make($createdCourse));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return ApiResponse
     */
    public function show(int $id): ApiResponse {
        $course = $this->courseService->show($id);

        return new ApiResponse(CourseResource::make($course));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CourseUpdateRequest $request
     * @param  int  $id
     * @return ApiResponse
     */
    public function update(CourseUpdateRequest $request, int $id): ApiResponse {
        $updatedCourse = $this->courseService->update($request->getDTO(), $id);

        if (!$updatedCourse)
        {
            return new ApiResponse('Invalid Provider', Response::HTTP_BAD_REQUEST, false);
        }

        return new ApiResponse(CourseResource::make($updatedCourse));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  CourseDeleteRequest  $request
     * @return ApiResponse
     */
    public function destroy(CourseDeleteRequest $request, int $id): ApiResponse {
       $this->courseService->delete($id);

       return new ApiResponse('Successfully delete course', ResponseAlias::HTTP_OK);
    }
}
