<?php

namespace App\Http\Resources;

use App\Models\PairExercise;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

final class PictureExerciseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => Storage::disk('public')->url($this->image_path),
            'options' => $this->shuffleOptions($this->option_json),
            'solved' => $this->pivot->solved ?? null,
        ];
    }

    private function shuffleOptions($answerSetJson)
    {
        $options = json_decode($answerSetJson, true);
        shuffle($options);

        return $options;
    }
}
