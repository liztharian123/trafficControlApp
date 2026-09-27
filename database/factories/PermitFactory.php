<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quote_id'      => Quote::factory(),
            'created_by'    => User::factory(),
            'permit_number' => null,
            'authority'     => fake()->randomElement([
                'Gold Coast City Council', 'Brisbane City Council', 'Logan City Council', 'TMR',
            ]),
            'lodged_date'   => fake()->dateTimeBetween('-2 weeks', 'now'),
            'expiry_date'   => null,
            'status'        => 'pending',
            'document_path' => null,
            'notes'         => null,
        ];
    }

    /**
     * A permit that has already been approved — has a permit number and expiry.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'        => 'approved',
            'permit_number' => 'P-' . now()->year . '-' . fake()->unique()->numberBetween(1000, 9999),
            'expiry_date'   => fake()->dateTimeBetween('+1 month', '+6 months'),
        ]);
    }
}