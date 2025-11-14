<?php

namespace App\Livewire\ProductionSites;

use App\Models\ProductionSite;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductionSiteForm extends Form
{
    public ?ProductionSite $production_site;


    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['required', 'string'])]
    public $location;

    #[Validate(['required', 'string'])]
    public $type;

    #[Validate(['required', 'string'])]
    public $system_operating_status;

    #[Validate(['nullable', 'numeric'])]
    public $annual_production;

    #[Validate(['nullable', 'numeric'])]
    public $weekly_production;

    #[Validate(['nullable', 'numeric'])]
    public $buffer_tank_size;

    public function set(ProductionSite $production_site) {
        $this->production_site = $production_site;
        $this->fill($production_site->toArray());
    }

    public function save() {
        // ensure name isn't taken by another item
        $duplicate = ProductionSite::where('name', $this->name)->first();

        if (isset($duplicate)) {
            if ((isset($this->production_site) && $this->production_site->id !== $duplicate->id) || !isset($this->production_site)) {
                $this->addError('name', 'Production Site name already taken');
                return;
            }
        }

        $this->validate();

        if (!isset($this->production_site)) {
            $this->production_site = new ProductionSite();
        }

        $this->production_site->fill($this->all());

        $this->production_site->save();
    }
}
