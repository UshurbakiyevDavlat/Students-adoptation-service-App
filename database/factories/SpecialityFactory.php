<?php

namespace Database\Factories;

use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $dictSpecialities = [
            'Program Engineer',
            'Business',
            'Engineering',
            'Medicine',
            'Psychology',
            'Law',
            'Education',
            'Computer Science',
            'Cyber Security',
            'Devops'
        ];
        $randomElement = $this->faker->unique()->randomElement($dictSpecialities);

        while (Speciality::all()->has($randomElement)) {
            $randomElement = $this->faker->unique()->randomElement($dictSpecialities);
        }

        return [
            'title' => $randomElement
        ];
    }
}
