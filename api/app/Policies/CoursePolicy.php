<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function canManageEnrollment(User $user, Course $course, int $studentId)
    {
        return $user->id === $studentId || $user->id === $course->account_id;
    }
}
