<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class PictureExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create();

        $options = [];

        for ($i = 0; $i < 4; $i++) {
            $option = [
                'text' => $faker->text(10),
                'is_correct' => $i == 0,
            ];

            $options[] = $option;
        }

        $optionJson = json_encode($options);

        return [
            'image_path' => 'pictures/1/1.png',
            'option_json' => $optionJson,
        ];
    }
}
