<?php

namespace App\Services;

use App\Contracts\ProgressExerciseServiceContract;
use App\DataTransfers\ProgressExercise\DeleteProgressDTO;
use App\DataTransfers\SolvingExerciseDTO;
use App\Models\Course;
use App\Models\Exercise;
use App\Models\ProgressExercise;
use App\Models\Stage;
use App\Models\User;

class ProgressExerciseService implements ProgressExerciseServiceContract
{
    public function getUserCompletedExercises($user_id): \Illuminate\Database\Eloquent\Collection
    {
        return ProgressExercise::where('account_id', $user_id)->get();
    }

    public function deleteUserProgress(DeleteProgressDTO $deleteProgressRequest): bool
    {
        $progressExercise = ProgressExercise::where('account_id', $deleteProgressRequest->user_id)
            ->where('accounts_exercise_id', $deleteProgressRequest->exercise_id)
            ->where('solved', true)
            ->first();

        $progressExercise->solved = false;
        $progressExercise->course_attachment = false;

        return $progressExercise->save();
    }

    public function getProgressByStage($userId, $stageId): array|string
    {
        $stage = Stage::find($stageId);
        $user = User::find($userId);

        if (!$stage || !$user){

            return 'Сould not find information on the transferred data';
        }

        $exercises = $stage->exercises()->get();

        $courses = $user->courses;

        $courseIds = $courses->pluck('id')->toArray();

        if (!in_array($stage->course_id, $courseIds)) {

            return 'The stage doesnt belong to your courses';
        }

        $progress = [];

        foreach ($exercises as $exercise) {
            $userProgress = $exercise->progressExercises->where('account_id', $userId)->first();

            $progress[] = [
                'exercise' => $exercise->id,
                'exercise_type' => $exercise->exercise_type,
                'exercise_id' => $exercise->exercise_id,
                'user_progress' => $userProgress,
            ];

        }

        return $progress;
    }

    public function solveExercise(Exercise $exercise): bool
    {
        $progressExercise = ProgressExercise::where('accounts_exercise_id', $exercise->id)
            ->where('account_id', auth()->user()->id)
            ->firstOrNew();

        if (!$progressExercise->exists) {
            $progressExercise->account_id = auth()->user()->id;
            $progressExercise->accounts_exercise_id = $exercise->id;
        }

        $progressExercise->solved = true;
        $progressExercise->solved_at = now();
        $progressExercise->course_attachment = ($exercise->course_id !== null);
        $progressExercise->save();

        return true;
    }

    public function getCourseProgressForCurrentUser(Course $course): array|string
    {
        $user = auth()->user();

        if (!$user || !$course) {
            return 'Could not find information on the transferred data';
        }

        $stages = $course->stages;
        $progress = [];

        foreach ($stages as $stage) {
            $exercises = $stage->exercises;
            $stageProgress = 0;
            $totalExercises = $exercises->count();

            foreach ($exercises as $exercise) {
                $userProgress = $exercise->progressExercises->where('account_id', $user->id)->first();
                if ($userProgress && $userProgress->solved) {
                    $stageProgress++;
                }
            }

            $progress[] = [
                'stage' => $stage->id,
                'stage_title' => $stage->title,
                'stage_progress' => $stageProgress,
                'total_exercises' => $totalExercises,
            ];
        }

        return $progress;
    }

    public function getCountProgressOfAllExercisesForUser(User $user): array
    {
        $countExercisesWithoutCourse = Exercise::where('account_id', $user->id)
            ->whereNull('course_id')
            ->whereNull('stage_id')
            ->count();

        $countExercisesWithCourse = 0;

        foreach ($user->courses as $course) {
            foreach ($course->stages as $stage) {
                $countExercisesWithCourse += $stage->exercises->count();
            }
        }

        $countSolvedExercisesWithoutCourse = ProgressExercise::where('account_id', $user->id)
            ->whereHas('exercise', function ($query) {
                $query->where('course_attachment', 0);
            })
            ->count();

        $countSolvedExercisesWithCourse = ProgressExercise::where('account_id', $user->id)
            ->whereHas('exercise', function ($query) {
                $query->where('course_attachment', 1);
            })
            ->count();

        return [
            'countExercisesWithoutCourse' => $countExercisesWithoutCourse,
            'countExercisesWithCourse' => $countExercisesWithCourse,
            'countSolvedExercisesWithoutCourse' => $countSolvedExercisesWithoutCourse,
            'countSolvedExercisesWithCourse' => $countSolvedExercisesWithCourse
        ];
    }

    public function canUserProceedToNextStage(\Illuminate\Contracts\Auth\Authenticatable|User $user, Stage $stage) : bool
    {
        $previousStages = Stage::where('course_id', $stage->course_id)
            ->where('created_at', '<', $stage->created_at)
            ->get();

        foreach ($previousStages as $stage) {
            $exercises = $stage->exercises;

            foreach ($exercises as $exercise) {
                $progress = $exercise->progressExercises()->where('account_id', $user->id)->first();

                if (!$progress || !$progress->solved) {
                    return false;
                }
            }
        }

        return true;
    }
}
