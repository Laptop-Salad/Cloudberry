<?php

namespace App\Livewire\Trucks;

use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Truck;
use App\Models\TruckType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public ?Truck $truck;

    public TruckForm $form;

    public $show = false;

    #[On(['edit-truck'])]
    public function edit(Truck $truck) {
        $this->truck = $truck;
        $this->showForm();
    }

    public function showForm() {
        $this->form->reset();

        if (isset($this->truck)) {
            $this->form->set($this->truck);
        }

        $this->show = true;
    }

    public function save() {
        $this->form->save();
        $this->show = false;
        $this->form->reset();

        // notify parents to refresh
        $this->dispatch('refresh-trucks');
    }

    #[Computed]
    public function truckTypes() {
        return TruckType::all();
    }

    #[Computed]
    public function productionSites() {
        return ProductionSite::query()
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function deliveryCompanies() {
        return DeliveryCompany::query()
            ->orderBy('name')
            ->get();
    }

    public function render() {
        return view('livewire.trucks.create');
    }
}
