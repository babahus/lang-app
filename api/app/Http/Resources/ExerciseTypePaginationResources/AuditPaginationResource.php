<?php

namespace App\Http\Resources\ExerciseTypePaginationResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AuditPaginationResource extends JsonResource
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
            'data' => $this->getCollection()->transform(function ($audit) {
                return [
                    'id' => $audit->id,
                    'exercises_id' => $audit->exercise ? $audit->exercise->id : null,
                    'path' => Storage::disk('public')->url($audit->path),
                    'text'   => $audit->transcription,
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
}
