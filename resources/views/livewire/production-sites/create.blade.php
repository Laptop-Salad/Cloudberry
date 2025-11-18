<div>
    <flux:modal.trigger wire:click="showForm">
        <flux:button icon="truck">Create Production Site</flux:button>
    </flux:modal.trigger>

    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">{{isset($this->production_site) ? 'Manage' : 'Edit'}} Production Site</flux:heading>

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
                label="System Operating Status *"
                wire:model="form.system_operating_status"
            />

            <flux:input
                label="Annual Production"
                type="number"
                step="0.01"
                wire:model="form.annual_production"
            />

            <flux:input
                label="Weekly Production"
                type="number"
                step="0.01"
                wire:model="form.weekly_production"
            />

            <flux:input
                label="Buffer Tank Size"
                type="number"
                step="0.01"
                wire:model="form.buffer_tank_size"
            />

            <x-form.footer />
        </form>
    </flux:modal>
</div>
