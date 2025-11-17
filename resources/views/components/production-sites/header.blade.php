<x-page-heading
    :title="$this->production_site->name"
    subtitle="View and manage production site {{$this->production_site->name}}"
>
</x-page-heading>

<flux:navbar class="mb-8">
    <flux:navbar.item
        :href="route('production-sites.show', $this->production_site->id)"
        :current="request()->routeIs('production-sites.show')"
        wire:navigate
    >
        Dashboard
    </flux:navbar.item>

    <flux:navbar.item
        :href="route('production-sites.events', $this->production_site->id)"
        :current="request()->routeIs('production-sites.events')"
        wire:navigate
    >
        Events
    </flux:navbar.item>
</flux:navbar>
