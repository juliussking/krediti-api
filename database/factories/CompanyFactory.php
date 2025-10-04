<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'social_reason' => fake()->company(),
            'fantasy_name' => fake()->company(),
            'cnpj' => '00.000.000/0000-00',
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'status' => fake()->randomElement(['Ativo', 'Inativo']),
            'admin_id' => User::inRandomOrder()->first()->id
        ];
    }
}
