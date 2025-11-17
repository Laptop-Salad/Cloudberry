<div>
    <x-production-sites.header />


    <table class="basic-table mt-6">
        <thead>
        <tr>
            <th>Location</th>
            <th>Type</th>
            <th>Status</th>
            <th>Annual Production</th>
            <th>Weekly Production</th>
            <th>Buffer Tank Size</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td class="font-medium">{{ $this->production_site->location }}</td>
            <td>{{ $this->production_site->type }}</td>
            <td>{{ $this->production_site->system_operating_status }}</td>
            <td>{{ $this->production_site->annual_production }}</td>
            <td>{{ $this->production_site->weekly_production }}</td>
            <td>{{ $this->production_site->buffer_tank_size }}</td>
        </tr>
        </tbody>
    </table>
</div>
