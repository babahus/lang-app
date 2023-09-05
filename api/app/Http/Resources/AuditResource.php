<?php

namespace App\Http\Resources;

use App\Models\Audit;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

final class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request|Audit  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'id'     => $this->id,
          'path'   => Storage::disk('public')->url($this->path),
          'text'   => $this->transcription,
        ];
    }
}
