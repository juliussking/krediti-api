<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Solicitation>
 */
class SolicitationFactory extends Factory
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
            'user_id' => fake()->numberBetween(1, 10),
            'amount_requested' => fake()->randomFloat(2, 1000, 100000),
            'tax' => fake()->randomElement(['1.31', '1.27', '1.23', '1.19', '1.15']),
            'status' => fake()->randomElement(['Pendente', 'Aprovada', 'Recusada']),
            'total' => fake()->randomFloat(2, 1000, 100000),
            'company_id' => fake()->numberBetween(1, 10),

        ];
    }
}
