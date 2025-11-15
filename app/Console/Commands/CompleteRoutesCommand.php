<?php

namespace App\Console\Commands;

use App\Enums\RouteStatus;
use App\Enums\TruckStatus;
use App\Models\Route;
use Illuminate\Console\Command;

class CompleteRoutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'routes:complete 
                          {--route_id= : Complete a specific route by ID}
                          {--auto : Automatically complete routes past scheduled date}';

    /**
     * The console command description.
     */
    protected $description = 'Complete routes and release trucks back to available pool';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('route_id')) {
            return $this->completeSpecificRoute($this->option('route_id'));
        }

        if ($this->option('auto')) {
            return $this->autoCompleteRoutes();
        }

        $this->info('Please specify --route_id=X or --auto');
        return Command::FAILURE;
    }

    /**
     * Complete a specific route
     */
    private function completeSpecificRoute(int $routeId)
    {
        $route = Route::find($routeId);

        if (!$route) {
            $this->error("Route {$routeId} not found");
            return Command::FAILURE;
        }

        if ($route->status === RouteStatus::COMPLETED->value) {
            $this->warn("Route {$routeId} is already completed");
            return Command::SUCCESS;
        }

        $this->completeRoute($route);
        $this->info("Route {$routeId} completed successfully");

        return Command::SUCCESS;
    }

    /**
     * Automatically complete routes that are past their scheduled date
     */
    private function autoCompleteRoutes()
    {
        $routes = Route::where('status', RouteStatus::IN_PROGRESS->value)
            ->where('scheduled_at', '<=', now()->subHours(2)) // 2 hour buffer
            ->get();

        if ($routes->isEmpty()) {
            $this->info('No routes to auto-complete');
            return Command::SUCCESS;
        }

        $this->info("Found {$routes->count()} routes to complete");

        $bar = $this->output->createProgressBar($routes->count());
        $bar->start();

        foreach ($routes as $route) {
            $this->completeRoute($route);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Completed {$routes->count()} routes");

        return Command::SUCCESS;
    }

    /**
     * Mark route as completed and release truck
     */
    private function completeRoute(Route $route): void
    {
        $route->update([
            'status' => RouteStatus::COMPLETED->value,
            'completed_at' => now(),
        ]);

        // Check if all trips for this truck are complete
        $allTripsComplete = Route::where('truck_id', $route->truck_id)
            ->where('delivery_company_id', $route->delivery_company_id)
            ->where('week_number', $route->week_number)
            ->where('year', $route->year)
            ->where('status', '!=', RouteStatus::COMPLETED->value)
            ->doesntExist();

        // Only release truck if all trips are done
        if ($allTripsComplete && $route->truck) {
            $route->truck->update([
                'available_status' => TruckStatus::AVAILABLE->value,
                'production_site_id' => null,
                'delivery_company_id' => null,
            ]);

            $this->line(" - Truck {$route->truck->truck_plate} released");
        }
    }
}