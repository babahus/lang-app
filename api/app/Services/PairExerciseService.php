<?php

namespace App\Services;

use App\Contracts\PairExerciseServiceContract;
use App\Models\PairExercise;
use App\Models\ProgressExercise;

final class PairExerciseService implements PairExerciseServiceContract
{
    public function updatePairExercise(int $id, array $data): bool
    {
        $pairExercise = PairExercise::findOrFail($id);

        $pairExercise->correct_pair_json = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $pairExercise->save();
    }

    public function deletePairExercise(PairExercise $pairExercise): bool
    {
        $pairExercise = PairExercise::findOrFail($pairExercise->id);

        return $pairExercise->delete();
    }

    public function solvePair($solvingExerciseDTO, $exercise): bool|string
    {
        $userTranslations = json_decode($solvingExerciseDTO->data);

        $pairExercisecorrect = $exercise->pairExercise->correct_pair_json;

        $correctTranslations = json_decode($pairExercisecorrect);

        if (count($userTranslations) !== count($correctTranslations)) {
            $allCorrect = false;
        } else {
            $allCorrect = true;

            foreach ($userTranslations as $userTranslation) {
                $word = $userTranslation->word;
                $translation = $userTranslation->translation;

                $correctTranslation = collect($correctTranslations)->first(function ($item) use ($word) {
                    return $item->word === $word;
                });

                if (!$correctTranslation || $correctTranslation->translation !== $translation) {
                    $allCorrect = false;
                    break;
                }
            }
        }

        if (!$allCorrect) {
            return 'Incorrect answer, try again';

        }

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



}
