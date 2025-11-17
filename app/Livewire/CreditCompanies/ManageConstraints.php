<?php

namespace App\Livewire\CreditCompanies;

use App\Models\CreditCompany;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ManageConstraints extends Component
{
    public ?CreditCompany $credit_company;

    #[Validate(['required', 'string'])]
    public $type;

    #[Validate(['required', 'integer', 'numeric'])]
    public $condition;

    public $show = false;

    #[On(['manage-credit-company-constraints'])]
    public function manage(CreditCompany $credit_company) {
        $this->reset();
        $this->credit_company = $credit_company;
        $this->show = true;
    }

    public function addConstraint() {
        $this->validate();

        $constraints = $this->credit_company->constraints;
        $constraints[$this->type] = $this->condition;

        $this->credit_company->constraints = $constraints;
        $this->credit_company->save();

        $this->type = null;
        $this->condition = null;
    }

    public function delete($type) {
        $constraints = $this->credit_company->constraints;
        unset($constraints[$type]);

        $this->credit_company->constraints = $constraints;
        $this->credit_company->save();
    }

    public function render()
    {
        return view('livewire.credit-companies.manage-constraints');
    }
}
