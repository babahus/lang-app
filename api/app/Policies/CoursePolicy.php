<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function canManageEnrollment(User $user, Course $course, int $studentId): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('Root')) {
            return true;
        }

        return (($user->id === $studentId && $user->hasRole('User'))) || (($user->id === $course->account_id && $user->hasRole('Teacher')));
    }
}
