<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id'    => Client::factory(),
            'created_by'   => User::factory(),
            'reference_no' => 'Q-' . now()->year . '-' . fake()->unique()->numberBetween(1000, 9999),
            'site_address' => fake()->streetAddress() . ', ' .
                fake()->randomElement(['Surfers Paradise', 'Southport', 'Broadbeach', 'Robina', 'Brisbane City']) .
                ' QLD',
            'description'  => fake()->sentence(10),
            'start_date'   => fake()->dateTimeBetween('+3 days', '+2 weeks'),
            'end_date'     => fake()->dateTimeBetween('+3 weeks', '+5 weeks'),
            'amount'       => fake()->randomFloat(2, 500, 15000),
            'status'       => fake()->randomElement(['draft', 'sent', 'approved', 'rejected']),
        ];
    }
}