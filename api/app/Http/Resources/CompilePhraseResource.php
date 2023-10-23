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
            'data' => $this->getCollection()->transform(function ($compilePhrase) {
                return [
                    'id' => $compilePhrase->id,
                    'phrase' => $compilePhrase->phrase,
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
