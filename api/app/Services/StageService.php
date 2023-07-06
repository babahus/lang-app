<?php

namespace App\Services;

use App\Models\Stage;
use App\DataTransfers\Stages\CreateStageDTO;

class StageService {
    
    public function getAllStages() {
        return Stage::all();
    }

    public function createStage(CreateStageDTO $dto) {
        $data = [
            'title' => $dto->title,
            'description' => $dto->description,
            'course_id' => $dto->course_id,
        ];
        
        return Stage::create($data);
    }

    public function getStageById($id) {
        return Stage::find($id);
    }

    public function updateStage($id, array $data) {
        $stage = Stage::find($id);

        if (!$stage) {
            return null;
        }

        $stage->update($data);

        return $stage;
    }

    public function deleteStage($id) {
        $stage = Stage::find($id);

        if (!$stage) {
            return false;
        }

        return $stage->delete();
    }
}
