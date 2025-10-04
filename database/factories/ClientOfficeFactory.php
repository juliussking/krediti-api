<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientOffice>
 */
class ClientOfficeFactory extends Factory
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
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'zipcode' => fake()->postcode(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'neighbor' => fake()->city(),
            'number' => fake()->numberBetween(1, 1000),
            'cnpj' => '00.000.000/0000-00',
            'role' => fake()->randomElement(['Pedreiro', 'Motorista', 'Eletricista', 'Padeiro', 'Pintor', 'Gerente', 'Repositor', 'Encanador', 'Bombeiro', 'Caixa de supermercado', 'Camelô']),
            'salary' => fake()->randomFloat(2, 1000, 100000),
            'payment_date' => fake()->date(),
            'admission_date' => fake()->date(),
        ];
    }
}
