<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <livewire:map-component />
        </div>
        <div class="flex space-x-2">
            <flux:input
                label="Site Search"
            />

            <flux:input
                label="Truck Search"
            />
        </div>

        <div class="lg:grid grid-cols-2">
            <livewire:dashboard.upcoming-events />
        </div>
    </div>
</x-layouts.app>
