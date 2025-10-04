<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientAddress>
 */
class ClientAddressFactory extends Factory
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
            'zipcode' => fake()->postcode(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'neighbor' => fake()->city(),
            'number' => fake()->numberBetween(1, 1000),
            'reference_point' => fake()->sentence(),
        ];
    }
}
