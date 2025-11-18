<?php

namespace App\Livewire\ProductionSites;

use App\Livewire\ProductionSites\ProductionSiteForm;
use App\Models\CreditCompany;
use App\Models\ProductionSite;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public ?ProductionSite $production_site;

    public ProductionSiteForm $form;

    public $show = false;

    #[On(['edit-production-site'])]
    public function edit(ProductionSite $production_site) {
        $this->production_site = $production_site;
        $this->form->set($production_site);
        $this->showForm();
    }

    public function showForm() {
        $this->form->reset();

        if (isset($this->production_site)) {
            $this->form->set($this->production_site);
        }

        $this->show = true;
    }

    public function save() {
        $this->form->save();
        $this->show = false;
        $this->form->reset();

        // notify parents to refresh
        $this->dispatch('refresh-production-sites');
    }

    public function render()
    {
        return view('livewire.production-sites.create');
    }
}
