<?php

namespace Database\Factories;

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
            'client_id' => fake()->numberBetween(1, 10),
            'zipcode' => fake()->postcode(),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'neighbor' => fake()->city(),
            'number' => fake()->numberBetween(1, 1000),
            'reference_point' => fake()->sentence(),
        ];
    }
}
