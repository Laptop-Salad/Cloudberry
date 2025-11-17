<div
    x-data="{
        initPrint() {
            @if($this->print_now != 0)
                window.print();
            @endif
        }
    }"
    x-init="initPrint()"
>
    <x-page-heading
        title="Week {{$this->weekly_plan_no}}"
        subtitle="View weekly plan {{$this->weekly_plan_no}}"
    >
        <flux:button x-on:click="window.print()" icon="printer">Print</flux:button>
    </x-page-heading>

    <table class="basic-table mt-6">
        <thead>
        <tr>
            <th>Truck</th>
            <th>Production Site</th>
            <th>Delivery Company</th>
            <th>Scheduled At</th>
            <th>Completed At</th>
            <th>Fuel</th>
            <th>Emission</th>
            <th>Cost</th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->routes as $route)
            <tr>
                <td>{{$route->truck->truck_plate}}</td>

                <td>
                    <p>{{$route->productionSite->name}}</p>
                    <p class="text-sm">{{$route->productionSite->location}}</p>
                </td>

                <td>
                    <p>{{$route->deliveryCompany->name}}</p>
                    <p class="text-sm">{{$route->deliveryCompany->location}}</p>
                </td>

                <td>
                    <p>{{$route->scheduled_at?->format('d-m-Y')}}</p>
                </td>

                <td>
                    <p>{{$route->completed_at?->format('d-m-Y')}}</p>
                </td>

                <td>
                    <p>{{$route->fuel}}</p>
                </td>

                <td>
                    <p>{{$route->emission}}</p>
                </td>

                <td>
                    <p>£{{$route->cost}}</p>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
