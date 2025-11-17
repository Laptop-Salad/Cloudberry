<div>
    <x-page-heading
        title="Credit Companies"
        subtitle="View and manage all credit companies"
    >
        <livewire:credit-companies.create />
    </x-page-heading>

    <table class="basic-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Credits Purchased</th>
            <th>LCA</th>
            <th>C02 Required</th>
            <th>Target Delivery Year</th>
            <th class="w-8"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->credit_companies as $credit_company)
            <tr>
                <td class="font-medium">
                    {{$credit_company->name}}
                    <p class="text-sm font-normal">{{$credit_company->type}}</p>
                </td>
                <td>{{$credit_company->credits_purchased}}</td>
                <td>{{$credit_company->lca}}</td>
                <td>{{$credit_company->co2_required}}</td>
                <td>{{$credit_company->target_delivery_year->format('Y')}}</td>
                <td>
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down">Options</flux:button>

                        <flux:menu>
                            <flux:menu.item
                                icon="cog-6-tooth"
                                wire:click="$dispatch('edit-credit-company', { 'credit_company' : {{$credit_company->id}}})"
                            >
                                Manage
                            </flux:menu.item>

                            <flux:menu.item
                                icon="view-columns"
                                wire:click="$dispatch('manage-credit-company-constraints', { 'credit_company' : {{$credit_company->id}}})"
                            >
                                Constraints
                            </flux:menu.item>

                            <flux:menu.item
                                icon="document-duplicate"
                                wire:click="$dispatch('duplicate-credit-company', { 'credit_company' : {{$credit_company->id}}})"
                            >
                                Duplicate
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <livewire:credit-companies.manage-constraints />
</div>
