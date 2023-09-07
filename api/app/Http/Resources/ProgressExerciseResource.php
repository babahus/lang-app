<?php

namespace App\Http\Resources;

use App\Enums\ExercisesResourcesTypes;
use App\Models\Audit;
use App\Models\CompilePhrase;
use App\Models\PairExercise;
use App\Models\PictureExercise;
use App\Models\Sentence;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressExerciseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'accounts_exercise_id' => $this->accounts_exercise_id,
            'user' => $this->user->only(['id', 'name']),
            'exercise' => match ($this->exercise->exercise_type) {
                ExercisesResourcesTypes::COMPILE_PHRASE->value => new CompilePhraseResource(CompilePhrase::find($this->exercise->exercise_id)),
                ExercisesResourcesTypes::AUDIT->value => new AuditResource(Audit::find($this->exercise->exercise_id)),
                ExercisesResourcesTypes::PAIR_EXERCISE->value => new PairExerciseResource(PairExercise::find($this->exercise->exercise_id)),
                ExercisesResourcesTypes::PICTURE_EXERCISE->value => new PictureExerciseResource(PictureExercise::find($this->exercise->exercise_id)),
                ExercisesResourcesTypes::SENTENCE->value => new SentenceResource(Sentence::find($this->exercise->exercise_id)),
                default => null,
            },
            'solved' => $this->solved,
        ];
    }
}
