<?php

namespace App\Livewire\ProductionSites;

use App\Models\ProductionSite;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public ProductionSite $production_site;

    public function render()
    {
        return view('livewire.production-sites.show');
    }
}
