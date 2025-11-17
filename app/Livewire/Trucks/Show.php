<?php

namespace App\Livewire\Trucks;

use App\Models\Truck;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public Truck $truck;

    public function render()
    {
        return view('livewire.trucks.show');
    }
}
