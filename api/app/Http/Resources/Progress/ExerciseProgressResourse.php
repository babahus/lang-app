<?php

namespace App\Http\Resources\Progress;

use App\Enums\ExercisesResourcesTypes;
use App\Http\Resources\AuditResource;
use App\Http\Resources\CompilePhraseResource;
use App\Http\Resources\DictionaryResource;
use App\Http\Resources\PairExerciseResource;
use App\Http\Resources\PictureExerciseResource;
use App\Http\Resources\ProgressExerciseResource;
use App\Http\Resources\SentenceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseProgressResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'type' => strtolower(ExercisesResourcesTypes::from($this->exercise_type)->name),
            'progress_exercises' => ProgressExerciseProgressResourse::collection($this->progressExercises),
            'data' => match ($this->exercise_type) {
                ExercisesResourcesTypes::DICTIONARY->value => new DictionaryResource($this->exercise),
                ExercisesResourcesTypes::COMPILE_PHRASE->value => new CompilePhraseResource($this->compilePhrase ?? $this->exercise),
                ExercisesResourcesTypes::AUDIT->value => new AuditResource($this->audit ?? $this->exercise),
                ExercisesResourcesTypes::PAIR_EXERCISE->value => new PairExerciseResource($this->pairExercise ?? $this->exercise),
                ExercisesResourcesTypes::PICTURE_EXERCISE->value => new PictureExerciseResource($this->pictureExercise ?? $this->exercise),
                ExercisesResourcesTypes::SENTENCE->value => new SentenceResource($this->sentence ?? $this->exercise),
                default => null,
            },
        ];
    }
}
