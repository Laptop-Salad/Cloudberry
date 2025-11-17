<?php

namespace App\Livewire\CreditCompanies;

use App\Models\CreditCompany;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreditCompanyForm extends Form
{
    public ?CreditCompany $credit_company;

    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['nullable', 'numeric'])]
    public $credits_purchased;

    #[Validate(['nullable', 'numeric'])]
    public $co2_required;

    #[Validate(['nullable', 'numeric'])]
    public $lca;

    #[Validate(['nullable', 'date'])]
    public $target_delivery_year;

    public function set(CreditCompany $credit_company) {
        $this->credit_company = $credit_company;
        $this->fill($credit_company->toArray());

        $this->target_delivery_year = $credit_company->target_delivery_year?->format('Y-m-d');
    }

    public function save() {
        // ensure name isn't taken by another item
        $duplicate = CreditCompany::where('name', $this->name)->first();

        if (isset($duplicate)) {
            if ((isset($this->credit_company) && $this->credit_company->id !== $duplicate->id) || !isset($this->credit_company)) {
                $this->addError('name', 'Credit Company name already taken');
                return;
            }
        }

        $this->validate();

        if (!isset($this->credit_company)) {
            $this->credit_company = new CreditCompany();
        }

        $this->credit_company->fill($this->all());

        $this->credit_company->save();
    }
}
