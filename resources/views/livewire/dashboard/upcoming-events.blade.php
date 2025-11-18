<div class="bg-black relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
    <div class="bg-[#EFFF00] text-center">
        <flux:heading size="lg" class="font-semibold">Upcoming Production Site Events</flux:heading>
    </div>

    @foreach($this->events_in_one_week as $event)
        <div class="my-4 text-white text-center">
            <p class="font-semibold">{{$event->type->display()}} @ {{$event->productionSite->name}}</p>
            <p class="text-sm text-blue-300">
                {{$event->start_date->format('d-m-Y')}} -
                {{$event->end_date->format('d-m-Y')}}
            </p>
        </div>
    @endforeach
</div>
