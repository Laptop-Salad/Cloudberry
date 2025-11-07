<div>
    <flux:modal.trigger wire:click="showForm">
        <flux:button icon="truck">Create Credit Company</flux:button>
    </flux:modal.trigger>

    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">Create Credit Company</flux:heading>

        <form wire:submit="save" class="mt-6 space-y-6">
            <flux:input
                label="Name *"
                wire:model="form.name"
                required
            />

            <flux:input
                label="Credits Purchased"
                type="number"
                step="0.01"
                wire:model="form.credits_purchased"
                required
            />

            <flux:input
                label="CO2 Required"
                type="number"
                step="0.01"
                wire:model="form.co2_required"
                required
            />

            <flux:input
                label="LCA"
                type="number"
                step="0.01"
                wire:model="form.lca"
                required
            />

            <flux:input
                label="Target Delivery Year"
                type="date"
                step="0.01"
                wire:model="form.target_delivery_year"
                required
            />

            <x-form.footer />
        </form>
    </flux:modal>
</div>
