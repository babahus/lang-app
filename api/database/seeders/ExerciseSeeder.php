<?php

namespace Database\Seeders;

use App\Enums\ExercisesTypes;
use App\Models\CompilePhrase;
use App\Models\Course;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Stage;
use App\Models\Dictionary;
use App\Models\Audit;
use App\Models\PairExercise;
use App\Models\PictureExercise;
use App\Models\Sentence;
use Faker\Factory as Faker;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Sentence::factory(10)->create();
        \App\Models\CompilePhrase::factory(10)->create();
        \App\Models\PictureExercise::factory(10)->create();
        \App\Models\PairExercise::factory(10)->create();
        \App\Models\Audit::factory(10)->create();

        $teacher = User::factory()->create();
        auth()->login($teacher);
        $teacher->roles()->attach(Role::where('name', 'Teacher')->first());

        $courses = Course::factory()->count(3)->create(['account_id' => $teacher->id]);

        foreach ($courses as $course) {

            $stages = Stage::factory()->count(3)->create(['course_id' => $course->id]);

            $course->stages()->saveMany($stages);
        }
    }

}
