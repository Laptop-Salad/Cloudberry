<?php

namespace App\Livewire\Trucks;

use App\Enums\TruckStatus;
use App\Models\Truck;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TruckForm extends Form
{
    public ?Truck $truck;

    #[Validate(['required', 'string', 'max:255'])]
    public $truck_plate;

    #[Validate(['required', 'numeric'])]
    public $co2_capacity;

    #[Validate(['required', 'numeric'])]
    public $available_status = TruckStatus::AVAILABLE->value;

    #[Validate(['required', 'exists:truck_types,id'])]
    public $truck_type_id;

    #[Validate(['nullable', 'exists:production_sites,id'])]
    public $production_site_id;

    #[Validate(['nullable', 'exists:delivery_companies,id'])]
    public $delivery_company_id;

    public function set(Truck $truck) {
        $this->truck = $truck;
        $this->fill($truck->toArray());
    }

    public function save() {
        // validate available_status is in enum
        $this->validate([
            'available_status' => ['required', new Enum(TruckStatus::class)],
        ]);

        // ensure plate isnt taken by another item
        $other_plate = Truck::where('truck_plate', $this->truck_plate)->first();

        if ($other_plate->exists()) {
            if ((isset($this->truck) && $this->truck->id !== $other_plate->id) || !isset($this->truck)) {
                $this->addError('truck_plate', 'Truck plate already taken');
                return;
            }
        }

        $this->validate();

        if (!isset($this->truck)) {
            $this->truck = new Truck();
        }

        $this->truck->fill($this->all());

        $this->truck->save();
    }
}
