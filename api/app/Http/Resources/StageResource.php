<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class StageResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'stage_exercises' => ExerciseResource::collection($this->exercises),
        ];
    }
}
