<?php

namespace App\Livewire\WeeklyPlans;

use App\Models\CreditCompany;
use App\Models\Route;
use Carbon\Carbon;
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

    public function render()
    {
        return view('livewire.weekly-plans.index');
    }
}
