<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '123456', // password
            'lastname' => $this->faker->lastname,
            'passport' => $this->faker->unique()->numberBetween(10000000,99999999),
            'phone' => $this->faker->unique()->numberBetween(10000000,99999999),
            'avatar' => Str::random(10),
        ];
    }
}
