<?php

namespace App\Livewire\Dashboard;

use App\Models\ProdSiteEvent;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UpcomingEvents extends Component
{
    #[Computed]
    public function eventsInOneWeek() {
        return ProdSiteEvent::query()
            ->where('start_date', '<=', now()->addWeek())
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.upcoming-events');
    }
}
