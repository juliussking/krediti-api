<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
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
            'client_id' => Client::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'amount_requested' => fake()->randomFloat(2, 1000, 100000),
            'tax' => fake()->randomElement(['1.31', '1.27', '1.23', '1.19', '1.15']),
            'status' => fake()->randomElement(['Pendente', 'Aprovada', 'Recusada']),
            'total' => fake()->randomFloat(2, 1000, 100000),
            'company_id' => Company::inRandomOrder()->first()->id,


        ];
    }
}
