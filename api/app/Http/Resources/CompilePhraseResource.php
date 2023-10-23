<?php

namespace App\Http\Resources;

use App\Models\CompilePhrase;
use Illuminate\Http\Resources\Json\JsonResource;

final class CompilePhraseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request|CompilePhrase   $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'phrase' => $this->phrase,
        ];
    }
}
