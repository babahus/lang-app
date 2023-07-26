<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'type' => $this->exercise_type,
            'data' => null,
        ];

        if ($this->exercise_type === 'App\Models\Dictionary') {
            $response['data'] = new DictionaryResource($this->dictionary);
        } elseif ($this->exercise_type === 'App\Models\CompilePhrase') {
            $response['data'] = new CompilePhraseResource($this->compilePhrase);
        } elseif ($this->exercise_type === 'App\Models\Audit') {
            $response['data'] = new AuditResource($this->audit);
        }

        return $response;
    }
}
