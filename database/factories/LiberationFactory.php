<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
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
            'user_id' => User::inRandomOrder()->first()->id,
            'client_id' => Client::inRandomOrder()->first()->id,
            'amount' => fake()->randomFloat(2, 1000, 100000),
            'status' => fake()->randomElement(['À vencer', 'Aprovado', 'Vencido', 'Quitado']),
            'expiration_date' => fake()->date(),
            'company_id' => Company::inRandomOrder()->first()->id,
        ];
    }
}
