@props([
    'title',
    'subtitle' => null,
])

<div class="grid grid-cols-3 items-center mb-4">
    <div class="justify-self-start">
        @isset($slot)
            {{ $slot }}
        @endisset
    </div>

    <div class="text-center">
        <flux:heading size="xl">{{ $title }}</flux:heading>

        @if ($subtitle)
            <flux:subheading>{{ $subtitle }}</flux:subheading>
        @endif
    </div>


    <div></div>
</div>

<flux:separator />
