<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            'liberation_id' => fake()->numberBetween(1, 10),
            'amount' => fake()->randomFloat(2, 1000, 100000),
            'payment_type' => fake()->randomElement(['Fator', 'Resgate', 'Abatimento']),
            'company_id' => fake()->numberBetween(1, 10),

        ];
    }
}
