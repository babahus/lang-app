<?php

namespace App\Observers;

use App\Enums\ExercisesTypes;
use App\Models\ChangeLog;
use App\Models\CompilePhrase;
use App\Models\Dictionary;
use App\Models\Exercise;
class ExerciseObserver
{
    public function created(Exercise $exercise)
    {
        $this->logChange($exercise, 'create');
    }

    public function deleted(Exercise $exercise)
    {
        $this->logChange($exercise, 'delete');
    }

    protected function logChange(Exercise $exercise, $operation)
    {
        $oldValues = $exercise->getOriginal();
        $newValues = $exercise->getAttributes();

        ChangeLog::create([
            'model_name' => Exercise::class,
            'record_id' => $exercise->id,
            'user_id' => auth()->user()->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
            'operation' => $operation,
        ]);
    }
}
