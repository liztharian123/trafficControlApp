<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    private const ENDPOINT = 'https://nominatim.openstreetmap.org/search';

    /**
     * @return array{lat: float, lng: float, formatted: string}|null
     */
    public function geocode(string $address): ?array
    {
        $response = Http::withHeaders([
                'User-Agent' => 'TrafficFlow/1.0 (Griffith University 7005ICT student project)',
            ])
            ->timeout(5)
            ->retry(2, 1000)
            ->get(self::ENDPOINT, [
                'q'            => $address,
                'format'       => 'jsonv2',
                'countrycodes' => 'au',
                'limit'        => 1,
            ]);

        $results = $response->json();

        if (! $response->ok() || empty($results)) {
            Log::warning('Geocode failed', [
                'address' => $address,
                'status'  => $response->status(),
            ]);

            return null;
        }

        $first = $results[0];

        return [
            'lat'       => (float) $first['lat'],
            'lng'       => (float) $first['lon'],
            'formatted' => $first['display_name'],
        ];
    }
}
