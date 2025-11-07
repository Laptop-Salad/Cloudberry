<?php

namespace App\Livewire\DeliveryCompanies;

use App\Models\DeliveryCompany;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['refresh-credit-companies'])]
class Index extends Component
{
    use WithPagination;

    #[Computed]
    public function deliveryCompanies() {
        return DeliveryCompany::query()
            ->orderBy('name')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.delivery-companies.index');
    }
}

