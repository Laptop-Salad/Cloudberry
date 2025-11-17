<?php

namespace App\Livewire\WeeklyPlans;

use App\Models\Route;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On(['refresh-weekly-plans'])]
class Index extends Component
{
    use WithPagination;

    #[Computed]
    public function weeklyPlans() {
        return Route::orderByDesc('week_number')
            ->get()
            ->groupBy('week_number')
            ->map(function ($routes, $week_number) {
                return [
                    'week_number' => $week_number,
                    'routes' => $routes,
                    'total_cost' => $routes->sum('cost'),
                ];
            });
    }

    public function printPlan($week_number) {
        $this->redirectRoute('weekly-plans.show', [
            'weekly_plan_no' => $week_number,
            'print_now' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.weekly-plans.index');
    }
}
