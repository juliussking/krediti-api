<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\user_profile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'avatar' => 'https://randomuser.me/api/portraits/' . $this->faker->randomElement(['men', 'women']) . '/' . $this->faker->numberBetween(40, 60) . '.jpg',
            'birthday' => fake()->date(),
            'phone' => fake()->phoneNumber(),

        ];
    }
}
