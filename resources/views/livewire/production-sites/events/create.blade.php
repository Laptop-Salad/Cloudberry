<div>
    <flux:modal.trigger wire:click="showForm">
        <flux:button icon="truck">Create Event</flux:button>
    </flux:modal.trigger>

    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">{{isset($this->event) ? 'Manage' : 'Edit'}} Event</flux:heading>

        <form wire:submit="save" class="mt-6 space-y-6">
            <flux:select
                label="Credit Company"
                wire:model="form.type"
            >
                <flux:select.option value="">Choose event type</flux:select.option>

                @foreach(\App\Enums\ProductionSites\EventType::cases() as $event_type)
                    <flux:select.option :value="$event_type->value">
                        {{$event_type->display()}}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:input
                label="Start Date"
                wire:model="form.start_date"
                type="date"
                required
            />

            <flux:input
                label="End Date"
                wire:model="form.end_date"
                type="date"
                required
            />

            <x-form.footer />
        </form>
    </flux:modal>
</div>
