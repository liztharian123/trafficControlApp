<?php

namespace Database\Seeders;

use App\Models\Permit;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermitSeeder extends Seeder
{
    public function run(): void
    {
        $permitUser = User::where('email', 'permit@trafficflow.test')->firstOrFail();

        // One approved permit for every approved-and-not-yet-permitted quote,
        // except the first one — that one stays "awaiting permit" so the
        // Permit dashboard has something to demonstrate.
        Quote::awaitingPermit()->get()->skip(1)->each(function ($quote) use ($permitUser) {
            Permit::factory()->approved()->create([
                'quote_id'   => $quote->id,
                'created_by' => $permitUser->id,
            ]);
        });
    }
}