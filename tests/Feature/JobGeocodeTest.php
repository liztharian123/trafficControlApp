<?php

use App\Models\Permit;
use App\Models\User;
use Illuminate\Support\Facades\Http;

test('creating a job stores coordinates from the geocoder', function () {
    Http::fake(['nominatim.openstreetmap.org/*' => Http::response([
        [
            'lat'          => '-28.0',
            'lon'          => '153.4',
            'display_name' => 'Test St, QLD, Australia',
        ],
    ])]);

    $ops    = User::factory()->operations()->create();
    $permit = Permit::factory()->approved()->create();

    $this->actingAs($ops)->post(route('jobs.store'), [
        'permit_id'      => $permit->id,
        'scheduled_date' => today()->addDay()->toDateString(),
        'shift_start'    => '07:00',
        'shift_end'      => '15:00',
        'site_address'   => 'Test St, QLD',
    ]);

    $this->assertDatabaseHas('operations_jobs', ['permit_id' => $permit->id, 'latitude' => -28.0]);
});
