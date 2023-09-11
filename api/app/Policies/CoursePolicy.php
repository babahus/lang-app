<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

class CoursePolicy
{
    use HandlesAuthorization;

    public function canManageEnrollment(User $user, Course $course, int $studentId): bool
    {
        $userRoles = optional(Cache::get('users_role_' . $user->id));
        $objRole = Role::whereId($userRoles['role_id'])->firstOrFail();

        return match ($objRole->name) {
            'Teacher' => $user->id === $course->account_id,
            'User' => $user->id === $studentId,
            'Admin', 'Root' => true,
            default => false,
        };
    }
}
