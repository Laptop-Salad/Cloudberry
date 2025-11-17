<?php

namespace App\Livewire\DeliveryCompanies;

use App\Models\DeliveryCompany;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ManageConstraints extends Component
{
    public ?DeliveryCompany $delivery_company;

    #[Validate(['required', 'string'])]
    public $type;

    #[Validate(['required', 'integer', 'numeric'])]
    public $condition;

    public $show = false;

    #[On(['manage-delivery-company-constraints'])]
    public function manage(DeliveryCompany $delivery_company) {
        $this->reset();
        $this->delivery_company = $delivery_company;
        $this->show = true;
    }

    public function addConstraint() {
        $this->validate();

        $constraints = $this->delivery_company->constraints;
        $constraints[$this->type] = $this->condition;

        $this->delivery_company->constraints = $constraints;
        $this->delivery_company->save();

        $this->type = null;
        $this->condition = null;
    }

    public function delete($type) {
        $constraints = $this->delivery_company->constraints;
        unset($constraints[$type]);

        $this->delivery_company->constraints = $constraints;
        $this->delivery_company->save();
    }

    public function render()
    {
        return view('livewire.delivery-companies.manage-constraints');
    }
}
