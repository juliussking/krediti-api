<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'person_type' => fake()->randomElement(['Fisica', 'Juridica']),
            'status' => fake()->randomElement(['Ativo', 'Vencido', 'Quitado']),
            'company_id' => Company::inRandomOrder()->first()->id,
            
        ];
    }
}
