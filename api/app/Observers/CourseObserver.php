<?php

namespace App\Observers;

use App\Models\ChangeLog;
use App\Models\Course;

class CourseObserver
{
    public function created(Course $course)
    {
        $this->logChange($course, 'create');
    }

    public function updated(Course $course)
    {
        $this->logChange($course, 'update');
    }

    public function deleted(Course $course)
    {
        $this->logChange($course, 'delete');
    }

    protected function logChange(Course $course, $operation)
    {
        $oldValues = $course->getOriginal();
        $newValues = $course->getAttributes();

        ChangeLog::create([
            'model_name' => Course::class,
            'record_id' => $course->id,
            'user_id' => auth()->user()->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
            'operation' => $operation,
        ]);
    }
}
