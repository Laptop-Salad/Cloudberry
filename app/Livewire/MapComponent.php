<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

class MapComponent extends Component
{
    #[Computed]
    public function productionSites() {
        // todo: return long lat of production sites
    }

    #[Computed]
    public function deliveryCompanies() {
        // todo: return long lat of delivery companies
    }

    public function render()
    {
        return view('livewire.map-component');
    }
}
