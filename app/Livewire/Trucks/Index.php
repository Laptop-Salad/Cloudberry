<?php

namespace App\Livewire\Trucks;

use App\Models\Truck;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['refresh-trucks'])]
class Index extends Component
{
    use WithPagination;

    #[Computed]
    public function trucks() {
        return Truck::query()
            ->orderBy('truck_plate')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.trucks.index');
    }
}
