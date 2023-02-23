<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $dictCities = [
            'Astana',
            'Almaty',
            'Shymkent',
            'Karaganda',
            'Aktobe',
            'Taraz',
            'Pavlodar',
            'Kyzylorda',
            'Atyrau',
            'Kokshetau'
        ];
        return [
            'title' => $this->faker->unique()->randomElement($dictCities)
        ];
    }
}
