<?php

namespace App\Livewire\WeeklyPlans;

use App\Models\Route;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public $weekly_plan_no;

    #[Url]
    public $print_now = false;

    #[Computed]
    public function routes() {
        return Route::query()
            ->where('week_number', $this->weekly_plan_no)
            ->latest('scheduled_at')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.weekly-plans.show');
    }
}
