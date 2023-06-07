<?php

namespace Database\Factories;

use App\Models\City;
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
        $randomElement = $this->faker->unique()->randomElement($dictCities);

        while (City::all()->has($randomElement)) {
            $randomElement = $this->faker->unique()->randomElement($dictCities);
        }

        return [
            'title' => $randomElement
        ];
    }
}
