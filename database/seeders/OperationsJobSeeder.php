<?php

namespace Database\Seeders;

use App\Models\OperationsJob;
use App\Models\Permit;
use App\Models\User;
use Illuminate\Database\Seeder;

class OperationsJobSeeder extends Seeder
{
    public function run(): void
    {
        $opsCreator = User::where('email', 'ops@trafficflow.test')->firstOrFail();
        $opsUsers   = User::whereRelation('department', 'slug', 'operations')->get();

        $sites = [
            ['Surfers Paradise Blvd, Surfers Paradise QLD 4217', -28.0027, 153.4300],
            ['Nerang St, Southport QLD 4215',                    -27.9700, 153.4090],
            ['Hope Island Rd, Hope Island QLD 4212',             -27.8710, 153.3680],
            ['Ann St, Brisbane City QLD 4000',                   -27.4660, 153.0280],
        ];

        Permit::readyToSchedule()->get()->skip(1)->values()->each(function ($permit, $i) use ($sites, $opsCreator, $opsUsers) {
            [$addr, $lat, $lng] = $sites[$i % count($sites)];

            $job = OperationsJob::create([
                'permit_id'         => $permit->id,
                'created_by'        => $opsCreator->id,
                'scheduled_date'    => today()->addDays($i + 1),
                'shift_start'       => '07:00',
                'shift_end'         => '15:00',
                'site_address'      => $addr,
                'formatted_address' => $addr . ', Australia',
                'latitude'          => $lat,
                'longitude'         => $lng,
                'geocoded_at'       => now(),
                'status'            => 'scheduled',
            ]);

            $job->crew()->attach(
                $opsUsers->random(2)->pluck('id')->mapWithKeys(
                    fn ($id, $k) => [$id => ['site_role' => $k === 0 ? 'supervisor' : 'controller']]
                )
            );
        });
    }
}