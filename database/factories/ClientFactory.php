<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
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
        'company_name' => fake()->company() . ' ' . fake()->randomElement(['Pty Ltd', 'Group', 'Constructions', 'Civil']),
        'contact_name' => fake()->name(),
        'email'        => fake()->unique()->companyEmail(),
        'phone'        => fake()->numerify('0445 657 684'),
    ];
}
}
