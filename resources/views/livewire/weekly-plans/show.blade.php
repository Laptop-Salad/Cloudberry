<div>
    <x-page-heading
        title="Week {{$this->weekly_plan_no}}"
        subtitle="View weekly plan {{$this->weekly_plan_no}}"
    >
        <flux:button x-on:click="window.print()" icon="printer">Print</flux:button>
    </x-page-heading>

    <flux:heading>Trucks</flux:heading>

    @foreach($this->routes as $route)
        <p>Truck</p>
        <p>{{$route->truck->truck_plate}}</p>

        <p>Production Site</p>
        <p>{{$route->productionSite->name}}</p>
        <p class="text-sm">{{$route->productionSite->location}}</p>

        <p>Production Site</p>
        <p>{{$route->deliveryCompany->name}}</p>
        <p class="text-sm">{{$route->deliveryCompany->location}}</p>

        <p>Scheduled At</p>
        <p>{{$route->scheduled_at?->format('d-m-Y')}}</p>

        <p>Completed At</p>
        <p>{{$route->completed_at?->format('d-m-Y')}}</p>

        {{-- NOTE: I know the wireframe doesnt specify date but I assume it needs to? If not then ignore this --}}

        <p>Fuel Used</p>
        <p>{{$route->fuel}}</p>

        <p>Emission</p>
        <p>{{$route->emission}}</p>

        <p>Cost</p>
        <p>£{{$route->cost}}</p>
    @endforeach
</div>
