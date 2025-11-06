@props([
    'title',
    'subtitle' => null,
])

<div class="flex items-center justify-between mb-4">
    <div>
        <flux:heading size="xl">{{$title}}</flux:heading>
        <flux:subheading>{{$subtitle}}</flux:subheading>
    </div>

    <div>
        @isset($slot)
            {{$slot}}
        @endisset
    </div>
</div>

<flux:separator />
