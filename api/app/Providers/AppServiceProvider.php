<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Stage;
use App\Observers\CourseObserver;
use App\Observers\ExerciseObserver;
use App\Observers\StageObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Exercise::observe(ExerciseObserver::class);
        Course::observe(CourseObserver::class);
        Stage::observe(StageObserver::class);
    }
}
