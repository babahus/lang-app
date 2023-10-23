<?php

namespace App\Http\Resources\ExerciseTypePaginationResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PictureExercisePaginationResource extends JsonResource
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
            'data' => $this->getCollection()->transform(function ($picture) {
                return [
                    'id' => $picture->id,
                    'path' => Storage::disk('public')->url($picture->image_path),
                    'options' => $this->shuffleOptions($picture->option_json),
                ];
            }),
            'pagination' => [
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
                'per_page'     => $this->perPage(),
                'next_page_url'=> $this->nextPageUrl(),
                'prev_page_url'=> $this->previousPageUrl(),
                'total'        => $this->total(),
                'from'         => $this->firstItem(),
                'to'           => $this->lastItem(),
            ],
        ];
    }

    private function shuffleOptions($answerSetJson)
    {
        $options = json_decode($answerSetJson, true);
        shuffle($options);

        return $options;
    }
}
