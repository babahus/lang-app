<?php

namespace App\Services;

use App\Models\Stage;
use App\DataTransfers\Stages\CreateStageDTO;
use Symfony\Component\HttpFoundation\Response;

class StageService
{
    public function getAllStages()
    {
        return Stage::all();
    }

    public function createStage(CreateStageDTO $dto)
    {
        $data = [
            'title' => $dto->title,
            'description' => $dto->description,
            'course_id' => $dto->course_id,
        ];

        return Stage::create($data);
    }

    public function getStageById($id)
    {
        return Stage::find($id);
    }

    public function updateStage($id, CreateStageDTO $dto)
    {
        $data = get_object_vars($dto);

        $stage = Stage::find($id);

        if (!$stage) {
            return null;
        }

        $stage->update($data);

        return $stage;
    }

    public function deleteStageById($id)
    {
        $stage = Stage::find($id);

        if (!$stage) {
            return ['success' => false, 'message' => 'Stage not found'];
        }

        if ($stage->account->id !== auth()->user()->id) {
            return ['success' => false, 'message' => 'You cannot delete this stage'];
        }

        $success = $stage->delete();

        return ['success' => true, 'message' => 'Stage deleted successfully'];
    }
}
