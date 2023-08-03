<?php

namespace App\Http\Resources;

use App\Enums\ExercisesResourcesTypes;
use App\Enums\ExercisesTypes;
use App\Http\Response\ApiResponse;
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
            'type' => strtolower(ExercisesResourcesTypes::from($this->exercise_type)->name),
            'data' => match ($this->exercise_type) {
                ExercisesResourcesTypes::DICTIONARY->value => new DictionaryResource($this->dictionary),
                ExercisesResourcesTypes::COMPILE_PHRASE->value => new CompilePhraseResource($this->compilePhrase),
                ExercisesResourcesTypes::AUDIT->value => new AuditResource($this->audit),
                ExercisesResourcesTypes::PAIR_EXERCISE->value => new PairExerciseResource($this->pairExercise),
                ExercisesResourcesTypes::PICTURE_EXERCISE->value => new PictureExerciseResource($this->pictureExercise),
                default => null,
            },
        ];

        return $response;
    }
}
