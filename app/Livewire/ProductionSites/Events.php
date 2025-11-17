<?php

namespace App\Livewire\ProductionSites;

use App\Models\ProdSiteEvent;
use App\Models\ProductionSite;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['refresh-prod-site-events'])]
class Events extends Component
{
    use WithPagination;

    #[Locked]
    public ProductionSite $production_site;

    #[Computed]
    public function events() {
        return ProdSiteEvent::query()
            ->where('production_site_id', $this->production_site->id)
            ->latest('start_date')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.production-sites.events');
    }
}
