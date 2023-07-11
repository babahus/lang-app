<?php

namespace App\Services;

use App\Models\Stage;
use App\DataTransfers\Stages\CreateStageDTO;

class StageService {
    
    public function getAllStages($courseId)
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            return null;
        }

        return Stage::where('course_id', $courseId)
            ->whereHas('course', function ($query) use ($currentUser) {
                $query->where('account_id', $currentUser->id);
            })->get();
    }


    public function createStage(CreateStageDTO $dto) {
        $data = [
            'title' => $dto->title,
            'description' => $dto->description,
            'course_id' => $dto->course_id,
        ];
        
        return Stage::create($data);
    }

    public function getStageById($id)
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            
            return null;
        }
        
        return Stage::where('id', $id)
            ->whereHas('course', function ($query) use ($currentUser) {
                $query->where('account_id', $currentUser->id);
            })
            ->get();
    }

    public function updateStage($id, CreateStageDTO $dto) {
        $data = get_object_vars($dto); 
        
        $stage = Stage::find($id);

        if (!$stage) {
            return null;
        }

        $stage->update($data);

        return $stage;
    }

    public function deleteStageById($id) {
        $stage = Stage::find($id);

        if (!$stage) {
            return null;
        }

        return $stage->delete();
    }
}
