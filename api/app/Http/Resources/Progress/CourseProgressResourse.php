<?php

namespace App\Http\Resources\Progress;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseProgressResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'courses' => $this->resource->getCollection()->transform(function($course) {
                return [
                    'id'            => $course->id,
                    'title'         => $course->title,
                    'description'   => $course->description,
                    'price'         => $course->price,
                    'course_stages' => StageProgressResourse::collection($course->stages),
                ];
            }),
            'pagination' => [
                'current_page' => $this->resource->currentPage(),
                'last_page'    => $this->resource->lastPage(),
                'per_page'     => $this->resource->perPage(),
                'next_page_url'=> $this->resource->nextPageUrl(),
                'prev_page_url'=> $this->resource->previousPageUrl(),
                'total'        => $this->resource->total(),
                'from'         => $this->resource->firstItem(),
                'to'           => $this->resource->lastItem(),
            ],
        ];
    }
}
