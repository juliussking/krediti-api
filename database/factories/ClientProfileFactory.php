<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\clientProfile>
 */
class ClientProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => fake()->numberBetween(1, 10),
            'avatar' => 'https://randomuser.me/api/portraits/' . $this->faker->randomElement(['men', 'women']) . '/' . $this->faker->numberBetween(1, 40) . '.jpg',
            'birth_date' => fake()->date(),
            'gender' => fake()->randomElement(['Masculino', 'Feminino']),
            'phone' => fake()->phoneNumber(),
            'marital_status' => fake()->randomElement(['Solteiro', 'Casado', 'Divorciado', 'Viuvo']),
        ];
    }
}
