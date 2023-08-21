<?php

namespace App\Observers;

use App\Models\ChangeLog;
use App\Models\Stage;

class StageObserver
{
    public function created(Stage $stage)
    {
        $this->logChange($stage, 'create');
    }

    public function updated(Stage $stage)
    {
        $this->logChange($stage, 'update');
    }

    public function deleted(Stage $stage)
    {
        $this->logChange($stage, 'delete');
    }

    protected function logChange(Stage $stage, $operation)
    {
        $oldValues = $stage->getOriginal();
        $newValues = $stage->getAttributes();

        ChangeLog::create([
            'model_name' => Stage::class,
            'record_id' => $stage->id,
            'user_id' => auth()->user()->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
            'operation' => $operation,
        ]);
    }
}
