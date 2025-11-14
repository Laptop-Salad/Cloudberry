<?php

namespace App\Livewire\CreditCompanies;

use App\Models\CreditCompany;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['refresh-credit-companies'])]
class Index extends Component
{
    use WithPagination;

    #[Computed]
    public function creditCompanies() {
        return CreditCompany::query()
            ->orderBy('name')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.credit-companies.index');
    }
}
