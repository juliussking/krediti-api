<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Liberation>
 */
class LiberationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 10),
            'client_id' => fake()->numberBetween(1, 10),
            'amount' => fake()->randomFloat(2, 1000, 100000),
            'status' => fake()->randomElement(['À vencer', 'Aprovado', 'Vencido', 'Quitado']),
            'expiration_date' => fake()->date(),
            'company_id' => fake()->numberBetween(1, 10),
        ];
    }
}
