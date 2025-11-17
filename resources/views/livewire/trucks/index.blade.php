<div>
    <x-page-heading
        title="Trucks"
        subtitle="View and manage all trucks"
    >
        <livewire:trucks.create />
    </x-page-heading>

    <table class="basic-table">
        <thead>
        <tr>
            <th>Description</th>
            <th>Capacity</th>
            <th>Production Site</th>
            <th>Delivery Company</th>
            <th>Status</th>
            <th class="w-8"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->trucks as $truck)
            <tr>
                <td class="font-medium">
                    <flux:link :href="route('trucks.show', $truck->id)">
                        {{$truck->truck_plate}}
                    </flux:link>
                </td>
                <td>{{$truck->co2_capacity}} Tonnes</td>
                <td>{{$truck->productionSite?->name ?? '-'}}</td>
                <td>{{$truck->deliveryCompany?->name ?? '-'}}</td>
                <td>
                    <x-trucks.status :status="$truck->available_status" />
                </td>
                <td>
                    <flux:button icon="cog-6-tooth" wire:click="$dispatch('edit-truck', { 'truck' : {{$truck->id}}})">Manage</flux:button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
