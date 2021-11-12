<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(5, true),
            'completed' => (bool) rand(0,1),
            'user_id' => User::first()->id,
            'updated_at' => date(rand(time(),time() - (60*60*24))),
            'deleted_at' => ((bool) rand(0,1)) ? now() : null
        ];
    }
}
