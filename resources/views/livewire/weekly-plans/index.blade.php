<div>
    <x-page-heading
        title="Weekly Plans"
        subtitle="View and manage all weekly plans"
    >
        <livewire:weekly-plans.create />
    </x-page-heading>

    <table class="basic-table">
        <thead>
        <tr>
            <th>Week</th>
            <th>Total Cost</th>
            <th class="w-8"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->weekly_plans as $weekly_plan)
            <tr>
                <td class="font-medium">
                    <flux:link :href="route('weekly-plans.show', ['weekly_plan_no' => $weekly_plan['week_number']])">
                        Week {{$weekly_plan['week_number']}}
                    </flux:link>
                </td>
                <td class="font-medium">
                    {{$weekly_plan['total_cost']}}
                </td>
                <td>
                    <flux:button
                        icon="printer"
                        wire:click="printPlan({{$weekly_plan['week_number']}})"
                    >
                        Print
                    </flux:button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
