<div>
    <flux:modal.trigger wire:click="showForm">
        <flux:button icon="truck">Create Event</flux:button>
    </flux:modal.trigger>

    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">Create Event</flux:heading>

        <form wire:submit="save" class="mt-6 space-y-6">
            <flux:input
                label="Week Number"
                wire:model="week_number"
                type="number"
                required
            />

            <flux:input
                label="Year"
                wire:model="year"
                type="number"
                required
            />

            <x-form.footer />
        </form>
    </flux:modal>
</div>
