<?php

namespace App\Livewire\WeeklyPlans;

use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate(['required', 'integer', 'numeric', 'min:1', 'max:53'])]
    public $week_number;

    #[Validate(['required', 'integer', 'numeric'])]
    public $year;

    public $show = false;

    public function showForm() {
        $this->week_number = Carbon::now()->isoWeek;
        $this->year = Carbon::now()->year;
        $this->show = true;
    }

    public function save() {
        $service = app(\App\Services\RouteOptimizationService::class);
        $result = $service->generateOptimisedRoutes($this->week_number, $this->year);

        if (isset($result['summary']['error'])) {
            $this->js('alert("Error: ' . $result['summary']['error'] . '")');
            return;
        }

        $this->show = false;

        // notify parents to refresh
        $this->dispatch('refresh-weekly-plans');
    }

    public function render()
    {
        return view('livewire.weekly-plans.create');
    }
}
