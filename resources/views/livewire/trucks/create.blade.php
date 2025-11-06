<div>
    <flux:modal.trigger wire:click="showForm">
        <flux:button icon="truck">Create Truck</flux:button>
    </flux:modal.trigger>

    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">Create Truck</flux:heading>

        <form wire:submit="save" class="mt-6 space-y-6">
            <flux:input
                label="Truck Plate *"
                wire:model="form.truck_plate"
                required
            />

            <flux:input
                label="CO2 Capacity *"
                type="number"
                step="0.01"
                wire:model="form.co2_capacity"
                required
            />

            <flux:select
                label="Truck Type *"
                wire:model="form.truck_type_id"
                required
            >
                <flux:select.option value="">Choose truck type</flux:select.option>

                @foreach($this->truck_types as $type)
                    <flux:select.option :value="$type->id">
                        {{$type->capacity}} Capacity, {{$type->count_available}} Count Available
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:select
                label="Production Site"
                wire:model="form.production_site_id"
            >
                <flux:select.option value="">Choose production site</flux:select.option>

                @foreach($this->production_sites as $site)
                    <flux:select.option :value="$site->id">
                        {{$site->name}}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:select
                label="Delivery Company"
                wire:model="form.delivery_company_id"
            >
                <flux:select.option value="">Choose delivery company</flux:select.option>

                @foreach($this->delivery_companies as $company)
                    <flux:select.option :value="$company->id">
                        {{$company->name}}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end space-x-2">
                <flux:button wire:click="show = false">Cancel</flux:button>
                <flux:button variant="primary" type="submit">Save</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
