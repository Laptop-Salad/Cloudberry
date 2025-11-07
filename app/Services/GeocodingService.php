<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeocodingService
{
    /**
     * Convert a postcode into latitude/longitude coordinates using Nominatim
     */
    public function geocodePostcode(string $postcode): ?array
    {
        // Check cache first (prevents hitting the API repeatedly)
        return Cache::remember("geocode_{$postcode}", now()->addDays(7), function () use ($postcode) {

            $response = Http::get("https://nominatim.openstreetmap.org/search", [
                'q' => $postcode,
                'format' => 'json',
                'limit' => 1,
            ]);

            if ($response->successful() && isset($response[0])) {
                return [
                    'lat' => (float) $response[0]['lat'],
                    'lng' => (float) $response[0]['lon'],
                ];
            }

            // Handle errors at the call site
            return null;
        });
    }
}
