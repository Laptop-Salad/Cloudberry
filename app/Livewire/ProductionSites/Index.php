<?php

namespace App\Livewire\ProductionSites;

use App\Models\ProductionSite;
use App\Models\Truck;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['refresh-production-sites'])]
class Index extends Component
{
    use WithPagination;

    #[Computed]
    public function productionSites() {
        return ProductionSite::query()
            ->orderBy('name')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.production-sites.index');
    }
}
