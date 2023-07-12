<?php

namespace App\Contracts;

use App\DataTransfers\Stages\CreateStageDTO;
use App\Models\Stage;

interface StageContract
{
    public function createStage(CreateStageDTO $dto): Stage;
    public function getStageById(int $id): ?Stage;
    public function updateStage(int $id, CreateStageDTO $dto): ?Stage;
    public function deleteStageById(int $id): bool;
}
