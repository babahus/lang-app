<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class PairExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $data = $this->generateValidPairData();

        return [
            'correct_pair_json' => json_encode($data),

        ];
    }

    private function generateValidPairData() {
        $faker = FakerFactory::create();

        $data = [];

        for ($i = 0; $i < 2; $i++) {
            $pair = [
                'word' => $faker->word,
                'translation' => $faker->word,
            ];

            $data[] = $pair;
        }

        return $data;
    }
}
