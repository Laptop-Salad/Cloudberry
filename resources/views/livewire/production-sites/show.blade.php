<div>
    <x-production-sites.header />

    {{-- NOTE: Not displaying shutdown periods here because we have the events tab --}}
    {{-- NOTE: Name displayed in header --}}

    <p>Location</p>
    <p>{{$this->production_site->location}}</p>

    <p>Type</p>
    <p>{{$this->production_site->type}}</p>

    <p>Status</p>
    <p>{{$this->production_site->system_operating_status}}</p>

    <p>Annual Production</p>
    <p>{{$this->production_site->annual_production}}</p>

    <p>Weekly Production</p>
    <p>{{$this->production_site->weekly_production}}</p>

    <p>Buffer Tank Size</p>
    <p>{{$this->production_site->buffer_tank_size}}</p>
</div>
