<?php

namespace App\Services;

use App\Models\PictureExercise;

final class PictureExerciseService
{
    public function updatePictureExercise(int $id, array $data): bool
    {
        $pictureExercise = PictureExercise::findOrFail($id);

        $pictureExercise->option_json = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $pictureExercise->save();
    }

    public function deletePictureExercise(PictureExercise $pictureExercise ): bool
    {
        $pictureExercise = PictureExercise::findOrFail($pictureExercise->id);

        return $pictureExercise->delete();
    }
}
