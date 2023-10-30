<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Arr;

class SentenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create();
        $sentence = $faker->sentence;

        $words = explode(' ', $sentence);
        $selectedWords = Arr::random($words, 2);

        return [
            'sentence_with_gaps' => $sentence,
            'correct_answers_json' => json_encode($selectedWords),
        ];
    }
}
