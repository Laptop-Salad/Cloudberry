<?php

namespace App\Livewire\DeliveryCompanies;

use App\Models\CreditCompany;
use App\Models\DeliveryCompany;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public ?DeliveryCompany $delivery_company;

    public DeliveryCompanyForm $form;

    public $show = false;

    #[On(['edit-delivery-company'])]
    public function edit(DeliveryCompany $delivery_company) {
        $this->delivery_company = $delivery_company;
        $this->showForm();
    }

    #[On(['duplicate-delivery-company'])]
    public function duplicate(DeliveryCompany $delivery_company) {
        $this->delivery_company = $delivery_company;
        $this->form->fill($this->delivery_company);
        $this->show = true;
    }

    public function showForm() {
        $this->form->reset();

        if (isset($this->delivery_company)) {
            $this->form->set($this->delivery_company);
        }

        $this->show = true;
    }

    public function save() {
        $this->form->save();
        $this->show = false;
        $this->form->reset();

        // notify parents to refresh
        $this->dispatch('refresh-delivery-companies');
    }


    #[Computed]
    public function creditCompanies() {
        return CreditCompany::query()
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.delivery-companies.create');
    }
}
