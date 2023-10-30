<?php

namespace Database\Factories;

use Faker\Factory as Faker;
use App\Enums\ExercisesTypes;
use App\Models\Audit;
use App\Models\CompilePhrase;
use App\Models\Course;
use App\Models\Dictionary;
use App\Models\Exercise;
use App\Models\PairExercise;
use App\Models\PictureExercise;
use App\Models\Sentence;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Exercise::class;

    public function definition()
    {
        $faker = Faker::create();

        $exerciseType = $faker->randomElement(ExercisesTypes::allValues());

        $exerciseId = null;

        switch ($exerciseType) {
            case ExercisesTypes::COMPILE_PHRASE:
                $exerciseId = CompilePhrase::inRandomOrder()->first()->id;
                break;
            case ExercisesTypes::SENTENCE:
                $exerciseId = Sentence::inRandomOrder()->first()->id;
                break;
            case ExercisesTypes::PICTURE_EXERCISE:
                $exerciseId = PictureExercise::inRandomOrder()->first()->id;
                break;
            case ExercisesTypes::PAIR_EXERCISE:
                $exerciseId = PairExercise::inRandomOrder()->first()->id;
                break;
            case ExercisesTypes::AUDIT:
                $exerciseId = Audit::inRandomOrder()->first()->id;
                break;
        }

        return [
            'account_id' => User::inRandomOrder()->first()->id,
            'course_id' => Course::inRandomOrder()->first()->id,
            'stage_id' => Stage::inRandomOrder()->first()->id,
            'exercise_id' => $exerciseId,
            'exercise_type' => $exerciseType,
        ];
    }
}
