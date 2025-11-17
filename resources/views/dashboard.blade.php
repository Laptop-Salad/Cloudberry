<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div>
            <livewire:map-component />
        </div>

        <div class="lg:grid grid-cols-2">
            <livewire:dashboard.upcoming-events />
        </div>
    </div>
</x-layouts.app>
