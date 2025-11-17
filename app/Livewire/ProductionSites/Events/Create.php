<?php

namespace App\Livewire\ProductionSites\Events;

use App\Models\ProdSiteEvent;
use App\Models\ProductionSite;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public ?ProductionSite $production_site;

    public ?ProdSiteEvent $event;

    public ProdSiteEventForm $form;

    public $show = false;

    #[On(['edit-prod-site-event'])]
    public function edit(ProdSiteEvent $event) {
        $this->event = $event;
        $this->showForm();
    }

    public function showForm() {
        $this->form->reset();

        if (isset($this->event)) {
            $this->form->set($this->event);
        }

        $this->show = true;
    }

    public function save() {
        $this->form->production_site_id = $this->production_site?->id;
        $this->form->save();
        $this->show = false;
        $this->form->reset();

        // notify parents to refresh
        $this->dispatch('refresh-prod-site-events');
    }

    public function render()
    {
        return view('livewire.production-sites.events.create');
    }
}
