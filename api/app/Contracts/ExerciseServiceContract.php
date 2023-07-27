<?php

namespace App\Contracts;

use App\DataTransfers\CreateExerciseDTO;
use App\DataTransfers\DeleteExerciseDTO;
use App\DataTransfers\MoveUserExerciseDTO;
use App\DataTransfers\SolvingExerciseDTO;
use App\DataTransfers\UpdateExerciseDTO;
use App\Models\User;

interface ExerciseServiceContract
{
    public function getAllExercises(int $userId);
    public function getExercisesByType(string $type, int $userId);
    public function getExerciseByIdAndType(string $type, int $id ,int $userId);
//    public function attach(MoveUserExerciseDTO $moveUserExerciseDTO, User $user);
    public function update(UpdateExerciseDTO $updateExerciseDTO, int $id);
    public function delete(DeleteExerciseDTO $deleteExerciseDTO, int $id);
    public function create(CreateExerciseDTO $createExerciseDTO);
    public function solving(SolvingExerciseDTO $solvingExerciseDTO);
    public function attachExerciseToStageCourse(MoveUserExerciseDTO $moveUserExerciseDTO);
    public function detachExerciseToStageCourse(MoveUserExerciseDTO $moveUserExerciseDTO);
}
