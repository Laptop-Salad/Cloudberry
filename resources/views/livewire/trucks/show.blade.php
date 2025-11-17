<div>
    <x-page-heading
            :title="$this->truck->truck_plate"
            subtitle="View truck {{ $this->truck->truck_plate }}"
    >
        <livewire:trucks.create />
    </x-page-heading>

    <table class="basic-table">
        <thead>
        <tr>
            <th>Description</th>
            <th>Capacity</th>
            <th>Truck Type</th>
            <th>Production Site</th>
            <th>Delivery Company</th>
            <th>Status</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td class="font-medium">{{ $this->truck->truck_plate }}</td>
            <td>{{ $this->truck->co2_capacity }} Tonnes</td>
            <td>{{ $this->truck->truckType->name }}</td>
            <td>{{ $this->truck->productionSite?->name ?? '-' }}</td>
            <td>{{ $this->truck->deliveryCompany?->name ?? '-' }}</td>
            <td>
                <x-trucks.status :status="$this->truck->available_status" />
            </td>
        </tr>
        </tbody>
    </table>

    <div class="mt-20"></div>

    <table class="basic-table mt-4">
        <thead>
        <tr>
            <th>Emissions</th>
            <th>Fuel Used</th>
            <th>Scheduled At</th>
            <th>Completed At</th>
            <th>Distance</th>
            <th>Cost</th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->truck->routes as $route)
            <tr>
                <td>{{ $route->emissions }}</td>
                <td>{{ $route->fuel_consumption }}</td>
                <td>{{ $route->scheduled_at?->format('d-m-Y') }}</td>
                <td>{{ $route->completed_at?->format('d-m-Y') }}</td>
                <td>{{ $route->distance }}</td>
                <td>£{{ $route->cost }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
