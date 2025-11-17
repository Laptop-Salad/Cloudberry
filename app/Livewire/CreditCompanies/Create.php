<?php

namespace App\Livewire\CreditCompanies;

use App\Models\CreditCompany;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public ?CreditCompany $credit_company;

    public CreditCompanyForm $form;

    public $show = false;

    #[On(['edit-credit-company'])]
    public function edit(CreditCompany $credit_company) {
        $this->credit_company = $credit_company;
        $this->showForm();
    }

    #[On(['duplicate-credit-company'])]
    public function duplicate(CreditCompany $credit_company) {
        $this->credit_company = $credit_company;
        $this->form->fill($this->credit_company);
        $this->form->target_delivery_year = $credit_company->target_delivery_year?->format('Y-m-d');
        $this->show = true;
    }

    public function showForm() {
        $this->form->reset();

        if (isset($this->credit_company)) {
            $this->form->set($this->credit_company);
        }

        $this->show = true;
    }

    public function save() {
        $this->form->save();
        $this->show = false;
        $this->form->reset();

        // notify parents to refresh
        $this->dispatch('refresh-credit-companies');
    }

    public function render()
    {
        return view('livewire.credit-companies.create');
    }
}
