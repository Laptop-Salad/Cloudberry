<div>
    <x-production-sites.header />

    <livewire:production-sites.events.create :$production_site />

    <table class="basic-table mt-8">
        <thead>
        <tr>
            <th>Type</th>
            <th>Date</th>
            <th class="w-8"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->events as $event)
            <tr>
                <td class="font-medium">{{$event->type->display()}}</td>
                <td>{{$event->start_date->format('Y-m-d')}} - {{$event->end_date->format('Y-m-d')}}</td>
                <td>
                    <flux:button
                        icon="cog-6-tooth"
                        wire:click="$dispatch('edit-prod-site-event', { 'event' : {{$event->id}}})"
                    >
                        Manage
                    </flux:button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
