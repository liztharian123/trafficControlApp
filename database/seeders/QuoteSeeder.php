<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();
        $quinn   = User::where('email', 'quote@trafficflow.test')->firstOrFail();
        $ava     = User::where('email', 'admin@trafficflow.test')->firstOrFail();

        // Quinn owns most quotes, spread deliberately across every status so
        // each dashboard filter has something to show.
        Quote::factory()->count(3)->recycle($clients)->create(['created_by' => $quinn->id, 'status' => 'draft']);
        Quote::factory()->count(3)->recycle($clients)->create(['created_by' => $quinn->id, 'status' => 'sent']);
        Quote::factory()->count(4)->recycle($clients)->create(['created_by' => $quinn->id, 'status' => 'approved']);
        Quote::factory()->count(1)->recycle($clients)->create(['created_by' => $quinn->id, 'status' => 'rejected']);

        // A couple belong to the admin, so in the viva you can demo that Quinn
        // (an ordinary Quote-dept user) cannot edit or delete Ava's (admin) quotes.
        Quote::factory()->count(2)->recycle($clients)->create(['created_by' => $ava->id, 'status' => 'approved']);
    }
}