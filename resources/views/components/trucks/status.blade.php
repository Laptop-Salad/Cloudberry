@props([
    'status',
])

<div class="{{$status->colour()}} p-1 text-center rounded-md border">
    {{$status->display()}}
</div>
