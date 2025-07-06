<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientReferenceContact>
 */
class ClientReferenceContactFactory extends Factory
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
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'relation' => fake()->randomElement(['Familia', 'Amigo', 'Trabalho', 'Outro']),
        ];
    }
}
