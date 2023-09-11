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
        $objRole = Role::whereId($userRoles['role_id'])->first();

        if ($objRole && $objRole->name === 'Teacher') {
            if ($user->id === $course->account_id) {
                return true;
            }
        }

        if ($objRole && $objRole->name === 'User') {
            if ($user->id === $studentId) {
                return true;
            }
        }

        if ($objRole && $objRole->name === 'Admin' || $objRole->name === 'Root') {
            return true;
        }

        return false;
    }
}
