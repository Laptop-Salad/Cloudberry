<div>
    <x-page-heading
        title="Users"
        subtitle="View and manage all users"
    >
    </x-page-heading>

    <table class="basic-table">
        <thead>
        <tr>
            <th>Name</th>
            <th class="w-8"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->users as $user)
            <tr>
                <td class="font-medium">{{$user->name}}</td>
                <td>
                    <flux:button icon="cog-6-tooth" wire:click="$dispatch('edit-user', { 'truck' : {{$user->id}}})">Manage</flux:button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
