<div>
    <flux:modal.trigger wire:click="showForm">
        <flux:button icon="truck">Create Delivery Company</flux:button>
    </flux:modal.trigger>

    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">{{isset($this->delivery_company) ? 'Manage' : 'Edit'}} Delivery Company</flux:heading>

        <form wire:submit="save" class="mt-6 space-y-6">
            <flux:input
                label="Name *"
                wire:model="form.name"
                required
            />

            <flux:input
                label="Location *"
                wire:model="form.location"
                required
            />

            <flux:input
                label="Type *"
                wire:model="form.type"
                required
            />

            <flux:input
                label="COD"
                wire:model="form.cod"
            />

            <flux:input
                label="Annual Minimum Obligation"
                type="number"
                step="0.01"
                wire:model="form.annual_min_obligation"
            />

            <flux:input
                label="Annual Maximum Obligation"
                type="number"
                step="0.01"
                wire:model="form.annual_max_obligation"
            />

            <flux:input
                label="Weekly Minimum Obligation"
                type="number"
                step="0.01"
                wire:model="form.weekly_min"
            />

            <flux:input
                label="Weekly Maximum Obligation"
                type="number"
                step="0.01"
                wire:model="form.weekly_max"
            />

            <flux:input
                label="Buffer Tank Size"
                type="number"
                step="0.01"
                wire:model="form.buffer_tank_size"
            />

{{--            <flux:select--}}
{{--                label="Credit Company"--}}
{{--                wire:model="form.credit_company_id"--}}
{{--            >--}}
{{--                <flux:select.option value="">Choose credit company</flux:select.option>--}}

{{--                @foreach($this->credit_companies as $company)--}}
{{--                    <flux:select.option :value="$company->id">--}}
{{--                        {{$company->name}}--}}
{{--                    </flux:select.option>--}}
{{--                @endforeach--}}
{{--            </flux:select>--}}

            <x-form.footer />
        </form>
    </flux:modal>
</div>
