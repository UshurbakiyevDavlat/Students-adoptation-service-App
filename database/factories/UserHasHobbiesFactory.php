<?php

namespace Database\Factories;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserHasHobbiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'hobby_id' => Hobby::all()->random()->id
        ];
    }
}
