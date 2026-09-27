<?php

namespace Database\Factories;

use App\Models\Permit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperationsJobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'permit_id'         => Permit::factory()->approved(),
            'created_by'        => User::factory(),
            'scheduled_date'    => fake()->dateTimeBetween('+3 days', '+3 weeks'),
            'shift_start'       => '07:00',
            'shift_end'         => '15:00',
            'site_address'      => fake()->streetAddress() . ', QLD',
            'formatted_address' => null,
            'latitude'          => null,
            'longitude'         => null,
            'geocoded_at'       => null,
            'status'            => 'scheduled',
            'notes'             => null,
        ];
    }
}