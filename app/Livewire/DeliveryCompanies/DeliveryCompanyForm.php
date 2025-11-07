<?php

namespace App\Livewire\DeliveryCompanies;

use App\Models\DeliveryCompany;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DeliveryCompanyForm extends Form
{
    public ?DeliveryCompany $delivery_company;

    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['required', 'string'])]
    public $location;

    #[Validate(['required', 'string'])]
    public $type;

    #[Validate(['nullable', 'string'])]
    public $cod;

    #[Validate(['nullable', 'numeric'])]
    public $annual_min_obligation;

    #[Validate(['nullable', 'numeric'])]
    public $annual_max_obligation;

    #[Validate(['nullable', 'numeric'])]
    public $weekly_min;

    #[Validate(['nullable', 'numeric'])]
    public $weekly_max;

    #[Validate(['nullable', 'numeric'])]
    public $buffer_tank_size;


    #[Validate(['required', 'exists:credit_companies,id'])]
    public $credit_company_id;

    public function set(DeliveryCompany $delivery_company) {
        $this->delivery_company = $delivery_company;
        $this->fill($delivery_company->toArray());
    }

    public function save() {
        // ensure name isn't taken by another item
        $duplicate = DeliveryCompany::where('name', $this->name)->first();

        if (isset($duplicate)) {
            if ((isset($this->delivery_company) && $this->delivery_company->id !== $duplicate->id) || !isset($this->delivery_company)) {
                $this->addError('name', 'Delivery Company name already taken');
                return;
            }
        }

        $this->validate();

        if (!isset($this->delivery_company)) {
            $this->delivery_company = new DeliveryCompany();
        }

        $this->delivery_company->fill($this->all());

        $this->delivery_company->save();
    }
}
