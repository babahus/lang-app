<?php

namespace App\Providers;

use App\DataTransfers\MoveUserExerciseDTO;
use App\Models\Course;
use App\Models\User;
use App\Policies\CoursePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('attach-detach-exercise', function (User $user, MoveUserExerciseDTO $moveUserExerciseDTO) {
            if ($user->hasRole('Teacher')) {
                if (isset($moveUserExerciseDTO->course_id)) {
                    $course = Course::find($moveUserExerciseDTO->course_id);

                    if (!$course) {
                        return false;
                    }

                    return true;
                }

                if (empty($moveUserExerciseDTO->course_id) && empty($moveUserExerciseDTO->stage_id)) {
                    return true;
                }
            }

            if ($user->hasRole('User')) {
                if (empty($moveUserExerciseDTO->course_id) && empty($moveUserExerciseDTO->stage_id)) {
                    return true;
                }
            }

            return false;
        });
    }
}
