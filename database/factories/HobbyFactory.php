<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HobbyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $dictHobbies = [
            'Reading books',
            'Playing sports',
            'Traveling',
            'Cooking',
            'Painting',
            'Gardening',
            'Playing musical instruments',
            'Photography',
            'Collecting',
            'Writing'
        ];
        return [
            'title' => $this->faker->unique()->randomElement($dictHobbies)
        ];
    }
}
