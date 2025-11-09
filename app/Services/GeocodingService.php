<?php

namespace App\Services;

use App\Models\PostcodeCache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeocodingService
{
    /**
     * Convert a postcode into latitude/longitude coordinates using Nominatim
     */
    public function geocodePostcode(string $postcode): ?array
    {
        $postcode = strtoupper(trim($postcode));

        // Check cache first (prevents hitting the API repeatedly)
        $cached = PostcodeCache::where('postcode', $postcode)->first();
        if ($cached) {
            return [
                'lat' => (float) $cached->latitude,
                'lng' => (float) $cached->longitude,
            ];
        }

        // Hit API only if not cached
        $response = Http::timeout(5)->withHeaders([
            'User-Agent' => 'CarbonCaptureRoutePlanner/1.0',
        ])->get("https://nominatim.openstreetmap.org/search", [
            'q' => $postcode,
            'format' => 'json',
            'limit' => 1,
        ]);

        if (!$response->successful() || !isset($response[0])) {
            return null;
        }

        $lat = (float)$response[0]['lat'];
        $lng = (float)$response[0]['lon'];

        // 3️⃣ Save result permanently
        PostcodeCache::create([
            'postcode' => $postcode,
            'latitude' => $lat,
            'longitude' => $lng,
        ]);

        return [
            'lat' => $lat,
            'lng' => $lng,
        ];
    }
}
