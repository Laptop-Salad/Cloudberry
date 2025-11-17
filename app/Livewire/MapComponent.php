<?php

namespace App\Livewire;

use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Services\GeocodingService;
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

    public function render()
    {
        return view('livewire.map-component');
    }
}
