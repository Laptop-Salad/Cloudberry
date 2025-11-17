<?php

namespace App\Livewire\ProductionSites\Events;

use App\Enums\ProductionSites\EventType;
use App\Models\ProdSiteEvent;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProdSiteEventForm extends Form
{
    public ?ProdSiteEvent $event;

    #[Validate(['required', 'exists:production_sites,id'])]
    public $production_site_id;

    #[Validate(['required', 'integer', 'numeric'])]
    public $type;

    #[Validate(['required', 'date', 'before:end_date'])]
    public $start_date;

    #[Validate(['required', 'date'])]
    public $end_date;

    public function set(ProdSiteEvent $event) {
        $this->event = $event;

        $this->fill($event->toArray());

        $this->start_date = $event->start_date?->format('Y-m-d');
        $this->end_date = $event->end_date?->format('Y-m-d');
    }

    public function save() {
        // validate type is in enum
        $this->validate([
            'type' => ['required', new Enum(EventType::class)],
        ]);

        $this->validate();

        if (!isset($this->event)) {
            $this->event = new ProdSiteEvent();
        }

        $this->event->fill($this->all());

        $this->event->save();
    }
}
