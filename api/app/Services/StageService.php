<?php

namespace App\Services;

use App\Models\Stage;

class StageService {
    
    public function getAllStages() {
        return Stage::all();
    }

    public function createStage(array $data) {

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
