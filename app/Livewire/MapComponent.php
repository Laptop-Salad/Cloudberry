<?php

namespace App\Livewire;

use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Route;
use App\Services\GeocodingService;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MapComponent extends Component
{
    #[Computed]
    public function productionSites() {
        $geocoding_service = new GeocodingService();

        return ProductionSite::all()->map(function ($site) use ($geocoding_service) {
            $coords = $geocoding_service->geocodePostcode($site->location);

            return [
                'name' => $site->name,
                'lat'  => $coords['lat'] ?? '',
                'lng'  => $coords['lng'] ?? '',
            ];
        });
    }

    #[Computed]
    public function deliveryCompanies() {
        $geocoding_service = new GeocodingService();

        return DeliveryCompany::all()->map(function ($site) use ($geocoding_service) {
            $coords = $geocoding_service->geocodePostcode($site->location);

            return [
                'name' => $site->name,
                'lat'  => $coords['lat'] ?? '',
                'lng'  => $coords['lng'] ?? '',
            ];
        });
    }

    #[Computed]
    public function routes() {
        $geocoding_service = new GeocodingService();

        return Route::query()
            ->where('week_number', Carbon::now()->isoWeek)
            ->get()
            ->map(function ($route) use ($geocoding_service) {
                return [
                    'id' => $route->id,
                    'from' => $geocoding_service->geocodePostcode($route->productionSite->location),
                    'to'   => $geocoding_service->geocodePostcode($route->deliveryCompany->location),
                    'truck_plate' => $route->truck->truck_plate,
                    'status'  => $route->completed_at ? 'complete' : 'pending',
                ];
            });
    }

    public function render()
    {
        return view('livewire.map-component');
    }
}
