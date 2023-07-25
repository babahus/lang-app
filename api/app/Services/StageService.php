<?php

namespace App\Services;

use App\Models\Stage;
use App\DataTransfers\Stages\CreateStageDTO;
use App\Contracts\StageContract;

class StageService implements StageContract
{

    public function getAllStages(int $courseId)
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


    public function createStage(CreateStageDTO $dto): Stage
    {
        $data = [
            'title' => $dto->title,
            'description' => $dto->description,
            'course_id' => $dto->course_id,
        ];

        return Stage::create($data);
    }

    public function getStageById(int $id): ?Stage
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            return null;
        }

        return Stage::where('id', $id)
            ->whereHas('course', function ($query) use ($currentUser) {
                $query->where('account_id', $currentUser->id);
            })
            ->first();
    }

    public function updateStage(int $id, CreateStageDTO $dto): ?Stage
    {
        $data = get_object_vars($dto);

        $stage = Stage::find($id);

        if (!$stage) {
            return null;
        }

        $stage->update($data);

        return $stage;
    }

    public function deleteStageById(int $id): bool {
        $stage = Stage::find($id);

        if (!$stage) {
            return null;
        }

        return $stage->delete();
    }
}
