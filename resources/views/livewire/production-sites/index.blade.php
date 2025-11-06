<div>
    <x-page-heading
        title="Production Sites"
        subtitle="View and manage all production sites"
    >
    </x-page-heading>

    <table class="basic-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Annual Production</th>
            <th>Weekly Production</th>
            <th>Buffer Tank Size</th>
            <th>System Operating Status</th>
            <th class="w-8"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->production_sites as $production_site)
            <tr>
                <td class="font-medium">
                    {{$production_site->name}}
                    <p class="text-sm font-normal">{{$production_site->type}}</p>
                </td>
                <td>{{$production_site->annual_production}} Tonnes</td>
                <td>{{$production_site->weekly_production}} Tonnes</td>
                <td>{{$production_site->buffer_tank_size}} Tonnes</td>
                <td>{{$production_site->system_operating_status}}</td>
                <td>
                    <flux:button
                        icon="cog-6-tooth"
                        wire:click="$dispatch('edit-production-site', { 'production-site' : {{$production_site->id}}})"
                    >
                        Manage
                    </flux:button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
