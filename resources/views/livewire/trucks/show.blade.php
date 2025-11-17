<div>
    <x-page-heading
        :title="$this->truck->truck_plate"
        subtitle="View and truck {{$this->truck->truck_plate}}"
    />

    <p>Description</p>
    <p>{{$this->truck->truck_plate}}</p>

    <p>Capacity</p>
    <p>{{$this->truck->co2_capacity}} Tonnes</p>

    <p>Truck Type</p>
    <p>{{$this->truck->truckType->name}}</p>

    <p>Production Site</p>
    <p>{{$this->truck->productionSite?->name}}</p>

    <p>Delivery Company</p>
    <p>{{$this->truck->deliveryCompany?->name}}</p>

    <p>Status</p>
    <p>{{$this->truck->available_status->display()}}</p>

    <flux:heading>Routes</flux:heading>

    @foreach($this->truck->routes as $route)
        <p>Emissions used</p>
        <p>{{$route->emissions}}</p>

        <p>Fuel used</p>
        <p>{{$route->fuel_consumption}}</p>

        <p>Scheduled At</p>
        <p>{{$route->scheduled_at?->format('d-m-Y')}}</p>

        <p>Completed At</p>
        <p>{{$route->completed_at?->format('d-m-Y')}}</p>

        <p>Distance</p>
        <p>{{$route->distance}}</p>

        <p>Cost</p>
        <p>£{{$route->cost}}</p>
    @endforeach
</div>
