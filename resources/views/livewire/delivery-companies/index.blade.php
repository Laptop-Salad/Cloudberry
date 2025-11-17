<div>
    <x-page-heading
        title="Delivery Companies"
        subtitle="View and manage all delivery companies"
    >
        <livewire:delivery-companies.create />
    </x-page-heading>

    <table class="basic-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>COD</th>
            <th>Annual Obligations</th>
            <th>Weekly Obligations</th>
            <th class="w-8"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->delivery_companies as $delivery_company)
            <tr>
                <td class="font-medium">
                    {{$delivery_company->name}}
                    <p class="text-sm font-normal">
                        {{$delivery_company->type}}
                        •
                        {{$delivery_company->buffer_tank_size}} Tonnes Buffer
                        •
                        {{$delivery_company->creditCompany?->name}}
                    </p>
                </td>
                <td>{{$delivery_company->location}}</td>
                <td>{{$delivery_company->cod}}</td>
                <td>{{$delivery_company->annual_min_obligation}} - {{$delivery_company->annual_max_obligation}}</td>
                <td>{{$delivery_company->weekly_min}} - {{$delivery_company->weekly_max}}</td>
                <td>
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down">Options</flux:button>

                        <flux:menu>
                            <flux:menu.item
                                icon="cog-6-tooth"
                                wire:click="$dispatch('edit-delivery-company', { 'delivery_company' : {{$delivery_company->id}}})"
                            >
                                Manage
                            </flux:menu.item>

                            <flux:menu.item
                                icon="view-columns"
                                wire:click="$dispatch('manage-delivery-company-constraints', { 'delivery_company' : {{$delivery_company->id}}})"
                            >
                                Constraints
                            </flux:menu.item>

                            <flux:menu.item
                                icon="document-duplicate"
                                wire:click="$dispatch('duplicate-delivery-company', { 'delivery_company' : {{$delivery_company->id}}})"
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

    <livewire:delivery-companies.manage-constraints />
</div>
