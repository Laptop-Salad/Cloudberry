<div>
    <flux:modal.trigger wire:click="showForm">
        <flux:button icon="truck">Create User</flux:button>
    </flux:modal.trigger>

    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">{{isset($this->user) ? 'Manage' : 'Edit'}} User</flux:heading>

        <form wire:submit="save" class="mt-6 space-y-6">
            <flux:input
                label="Name *"
                wire:model="form.name"
                required
            />

            <flux:input
                label="Email *"
                type="email"
                wire:model="form.email"
                required
            />

            <flux:select
                label="Role"
                wire:model="form.role_id"
            >
                <flux:select.option value="">Choose type</flux:select.option>

                @foreach($this->roles as $role)
                    <flux:select.option :value="$role->id">
                        {{$role->name}}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <x-form.footer />
        </form>
    </flux:modal>
</div>
