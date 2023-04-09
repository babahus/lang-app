<?php

namespace App\Http\Resources;

use App\Models\Audit;
use App\Models\CompilePhrase;
use App\Models\Dictionary;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

final class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request|Dictionary|CompilePhrase|Audit|User  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // todo : need to transform dara response to another response(without array)
        return [
          'dictionary_exercise'    => DictionaryResource::collection($this->dictionary()->get()),
          'compilePhrase_exercise' => CompilePhraseResource::collection($this->compilePhrase()->get()),
          'audit_exercise'         => AuditResource::collection($this->audit()->get())
        ];
    }
}
