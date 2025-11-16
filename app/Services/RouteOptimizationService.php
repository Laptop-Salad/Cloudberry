<?php

namespace App\Services;

use App\Enums\ConstraintType;
use App\Enums\RouteStatus;
use App\Enums\TruckStatus;
use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Route;
use App\Models\Truck;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RouteOptimizationService
{
    protected GeocodingService $geo;

    public function __construct(GeocodingService $geo)
    {
        $this->geo = $geo;
    }

    /**
     * Generate optimal routes based on available data
     * Returns detailed results with success/failure tracking
     */
    public function generateOptimisedRoutes(?int $weekNumber = null, ?int $year = null): array
    {
        // Default to current week
        $weekNumber = $weekNumber ?? now()->weekOfYear;
        $year = $year ?? now()->year;

        // Prevent generating routes for past weeks
        $currentWeek = now()->weekOfYear;
        $currentYear = now()->year;

        if ($year < $currentYear || ($year == $currentYear && $weekNumber < $currentWeek)) {
            return [
                'success' => [],
                'failed' => [],
                'summary' => [
                    'error' => 'Cannot generate routes for past weeks',
                    'requested_week' => $weekNumber,
                    'requested_year' => $year,
                    'current_week' => $currentWeek,
                    'current_year' => $currentYear,
                    'message' => 'Routes can only be generated for current week onwards',
                ],
            ];
        }

        // Check if trying to skip weeks
        $lastGeneratedWeek = Route::orderByDesc('year')
            ->orderByDesc('week_number')
            ->first();

        if ($lastGeneratedWeek) {
            $expectedNextWeek = $lastGeneratedWeek->week_number + 1;
            $expectedNextYear = $lastGeneratedWeek->year;

            // Handle year boundary
            if ($expectedNextWeek > 52) {
                $expectedNextWeek = 1;
                $expectedNextYear++;
            }

            if ($year != $expectedNextYear || $weekNumber != $expectedNextWeek) {
                return [
                    'success' => [],
                    'failed' => [],
                    'summary' => [
                        'error' => 'Cannot skip weeks',
                        'last_generated_week' => $lastGeneratedWeek->week_number,
                        'last_generated_year' => $lastGeneratedWeek->year,
                        'expected_next_week' => $expectedNextWeek,
                        'expected_next_year' => $expectedNextYear,
                        'message' => 'Must generate weeks sequentially',
                    ],
                ];
            }
        }

        // Auto-complete routes from previous weeks (releases resources)
        $this->autoCompletePreviousWeeks($weekNumber, $year);

        // Check if routes already exist for this week
        $existingRoutes = Route::where('week_number', $weekNumber)
            ->where('year', $year)
            ->exists();

        if ($existingRoutes) {
            return [
                'success' => [],
                'failed' => [],
                'summary' => [
                    'error' => 'Routes already generated for this week',
                    'week' => $weekNumber,
                    'year' => $year,
                    'message' => 'Use clearRouteCache() to regenerate if needed',
                ],
            ];
        }

        // Fetch only operational entities
        $trucks = Truck::where('available_status', TruckStatus::AVAILABLE->value)->get();

        $companies = DeliveryCompany::where(function($query) {
            $query->where('cod', 'Already Operating')
                ->orWhere('cod', 'like', 'Already%');
        })->get();

        $sites = ProductionSite::where(function($query) {
            $query->where('system_operating_status', 'Already operating')
                ->orWhere('system_operating_status', 'like', 'Already%');
        })->get();

        if ($trucks->isEmpty() || $companies->isEmpty() || $sites->isEmpty()) {
            return [
                'success' => [],
                'failed' => [],
                'summary' => [
                    'error' => 'Missing required data to optimise routes',
                    'trucks_available' => $trucks->count(),
                    'companies_operational' => $companies->count(),
                    'sites_operational' => $sites->count(),
                ],
            ];
        }

        $results = [
            'success' => [],
            'failed' => [],
            'summary' => [
                'total_companies' => $companies->count(),
                'routes_generated' => 0,
                'total_trips' => 0,
                'total_co2_allocated' => 0,
                'trucks_used' => 0,
            ],
        ];

        DB::beginTransaction();

        try {
            foreach ($companies as $company) {
                try {
                    $routeResult = $this->generateRouteForCompany(
                        $company,
                        $sites,
                        $trucks,
                        $weekNumber,
                        $year
                    );

                    if ($routeResult['success']) {
                        $results['success'] = array_merge($results['success'], $routeResult['routes']);
                        $results['summary']['routes_generated'] += count($routeResult['routes']);
                        $results['summary']['total_trips'] += $routeResult['trips'];
                        $results['summary']['total_co2_allocated'] += $routeResult['co2_delivered'];

                        if ($routeResult['truck_used']) {
                            $results['summary']['trucks_used']++;
                        }
                    } else {
                        $results['failed'][] = [
                            'company_id' => $company->id,
                            'company_name' => $company->name,
                            'reason' => $routeResult['reason'],
                            'details' => $routeResult['details'] ?? [],
                        ];
                    }
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'company_id' => $company->id,
                        'company_name' => $company->name,
                        'reason' => 'Unexpected error: ' . $e->getMessage(),
                        'details' => ['trace' => $e->getTraceAsString()],
                    ];

                    Log::error('Route generation failed for company', [
                        'company_id' => $company->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    /**
     * Generate route(s) for a single company
     */
    private function generateRouteForCompany(
        DeliveryCompany $company,
                        $sites,
                        $trucks,
        int $weekNumber,
        int $year
    ): array {
        // Calculate optimal capacity to deliver (between min and max)
        $capacityToDeliver = $this->calculateOptimalDeliveryCapacity($company);

        if ($capacityToDeliver <= 0) {
            return [
                'success' => false,
                'reason' => 'No weekly minimum obligation set',
            ];
        }

        // Check company buffer capacity
        $availableBuffer = $this->getCompanyAvailableBuffer($company, $weekNumber, $year);
        if ($availableBuffer < $capacityToDeliver) {
            return [
                'success' => false,
                'reason' => 'Insufficient buffer tank capacity',
                'details' => [
                    'required' => $capacityToDeliver,
                    'available_buffer' => $availableBuffer,
                    'buffer_size' => $company->buffer_tank_size,
                ],
            ];
        }

        // Find matching sites with capacity validation
        $matchingSites = $this->findSiteMatchingConstraints(
            $company,
            $sites,
            $capacityToDeliver,
            $weekNumber,
            $year
        );

        if ($matchingSites->isEmpty()) {
            return [
                'success' => false,
                'reason' => 'No production sites match constraints or have sufficient capacity',
                'details' => [
                    'required_capacity' => $capacityToDeliver,
                    'constraints' => $company->getReadableConstraints(),
                ],
            ];
        }

        // Choose best truck based on capacity needed
        $truck = $this->assignBestTruck($trucks, $capacityToDeliver);

        if (!$truck) {
            return [
                'success' => false,
                'reason' => 'No available trucks with sufficient capacity',
                'details' => [
                    'required_capacity' => $capacityToDeliver,
                    'available_trucks' => $trucks->count(),
                ],
            ];
        }

        // Choose best site based on distance/cost
        $matchingSite = $this->selectBestSite($matchingSites, $company, $truck);

        // Check site buffer availability
        $siteAvailableBuffer = $this->getSiteAvailableBuffer($matchingSite, $weekNumber, $year);
        if ($siteAvailableBuffer < $capacityToDeliver) {
            return [
                'success' => false,
                'reason' => 'Production site buffer tank insufficient',
                'details' => [
                    'site' => $matchingSite->name,
                    'required' => $capacityToDeliver,
                    'available_buffer' => $siteAvailableBuffer,
                ],
            ];
        }

        // Calculate trips needed
        $tripsNeeded = $this->calculateTripsNeeded($capacityToDeliver, $truck);

        // Calculate route metrics once
        $distance = $this->estimateDistance($matchingSite, $company);
        $fuelConsumptionPerKm = $truck->truckType->fuel_consumption_per_km;
        $emissionFactorPerKm = $truck->truckType->emission_factor;
        $fuelCostPerKm = $truck->truckType->fuel_cost_per_km;

        $fuelConsumption = $distance * $fuelConsumptionPerKm;
        $emissions = $distance * $emissionFactorPerKm;
        $cost = $distance * $fuelCostPerKm;
        $estimatedDuration = $this->calculateEstimatedDuration($distance);

        $generatedRoutes = [];
        $remainingCapacity = $capacityToDeliver;

        // Generate multiple routes if needed
        for ($trip = 1; $trip <= $tripsNeeded; $trip++) {
            $tripCapacity = min($truck->co2_capacity, $remainingCapacity);

            $route = Route::create([
                'truck_id' => $truck->id,
                'production_site_id' => $matchingSite->id,
                'delivery_company_id' => $company->id,
                'distance' => $distance,
                'fuel_consumption' => $fuelConsumption,
                'emissions' => $emissions,
                'cost' => $cost,
                'co2_delivered' => $tripCapacity,
                'status' => RouteStatus::PENDING->value,
                'week_number' => $weekNumber,
                'year' => $year,
                'trip_number' => $trip,
                'total_trips' => $tripsNeeded,
                'estimated_duration_minutes' => $estimatedDuration,
                'scheduled_at' => now()->addWeek()->startOfWeek()->addDays(($trip - 1)),
            ]);

            $generatedRoutes[] = $route->load(['truck.truckType', 'productionSite', 'deliveryCompany']);
            $remainingCapacity -= $tripCapacity;
        }

        // Mark truck as in transit (only once, not per trip)
        $truck->update([
            'available_status' => TruckStatus::IN_TRANSIT->value,
            'production_site_id' => $matchingSite->id,
            'delivery_company_id' => $company->id,
        ]);

        return [
            'success' => true,
            'routes' => $generatedRoutes,
            'trips' => $tripsNeeded,
            'co2_delivered' => $capacityToDeliver,
            'truck_used' => true,
        ];
    }

    /**
     * Calculate optimal delivery capacity (respects min/max constraints)
     * Returns a value between weekly_min and weekly_max
     */
    private function calculateOptimalDeliveryCapacity(DeliveryCompany $company): float
    {
        $weeklyMin = $company->weekly_min ?? 0;
        $weeklyMax = $company->weekly_max ?? $weeklyMin;

        // Validate constraints
        if ($weeklyMin <= 0) {
            return 0;
        }

        // If min == max, deliver exactly that amount
        if ($weeklyMin == $weeklyMax) {
            return $weeklyMin;
        }

        // If max is set and valid, ensure min doesn't exceed it
        if ($weeklyMax > 0 && $weeklyMin > $weeklyMax) {
            // Invalid data: min > max, use max
            return $weeklyMax;
        }

        // Strategy: Deliver the minimum required
        // (You can change this to deliver more efficiently, e.g., nearest truck capacity)
        return $weeklyMin;
    }

    /**
     * Match delivery constraints with production site CO₂ source
     */
    private function findSiteMatchingConstraints(
        DeliveryCompany $company,
                        $sites,
        float $requiredWeeklyCapacity,
        int $weekNumber,
        int $year
    ) {
        $constraints = $company->constraints ?? [];

        // If company references credit company constraints, merge them
        if (
            isset($constraints['delivery_condition']) &&
            $constraints['delivery_condition'] === ConstraintType::SEE_CREDIT_COMPANY_CONSTRAINTS->value
        ) {
            $creditCompanies = $company->creditCompanies;

            foreach ($creditCompanies as $creditCompany) {
                $creditConstraints = $creditCompany->constraints ?? [];
                $constraints = array_merge($constraints, $creditConstraints);
            }
        }

        // Map constraints to rule functions
        $constraintRules = [
            ConstraintType::NONE->value => fn($site) => true,

            ConstraintType::MUST_BE_DISTILLERY_SOURCE->value =>
                fn($site) => stripos($site->type, 'Distillery') !== false,

            ConstraintType::ACCEPTS_CO2_FROM_BIOGAS_NON_MANURE->value =>
                fn($site) => stripos($site->type, 'biogas') !== false
                    && stripos($site->type, 'manure') === false,

            ConstraintType::ACCEPTS_CO2_FROM_CMA_FULLY_TESTED->value =>
                fn($site) => $site->name === 'Cask Me Another'
                    || $site->name === 'Cask Me Another (Expansion)',

            ConstraintType::ACCEPTS_CO2_FROM_LL_FULLY_TESTED->value =>
                fn($site) => $site->name === 'Loch & Loaded',

            ConstraintType::MUST_BE_CARBONATION_OCO->value =>
                fn($site) => true, // Storage method constraint, not site-specific
        ];

        return $sites->filter(function ($site) use (
            $constraints,
            $constraintRules,
            $requiredWeeklyCapacity,
            $weekNumber,
            $year
        ) {
            // Parse weekly production (handles "60-70" format or numeric values)
            $weeklyProduction = $this->parseWeeklyProduction($site->weekly_production);

            // Check if site has enough weekly production baseline
            if ($weeklyProduction < $requiredWeeklyCapacity) {
                return false;
            }

            // Check remaining capacity for this week
            $remainingCapacity = $this->getRemainingWeeklyCapacity($site, $weekNumber, $year);
            if ($remainingCapacity < $requiredWeeklyCapacity) {
                return false;
            }

            // Apply constraint rules
            foreach ($constraints as $key => $constraint) {
                // Handle both array-style and direct constraint values
                $constraintValue = is_array($constraint) ? $key : $constraint;

                if (!isset($constraintRules[$constraintValue])) {
                    continue; // Skip unknown constraints
                }

                $rule = $constraintRules[$constraintValue];

                if (!$rule($site)) {
                    return false; // Site fails this constraint
                }
            }

            return true;
        });
    }

    /**
     * Select the best site from matching ones based on cost/distance
     */
    private function selectBestSite($matchingSites, DeliveryCompany $company, Truck $truck): ?ProductionSite
    {
        return $matchingSites
            ->map(function ($site) use ($company, $truck) {
                $distance = $this->estimateDistance($site, $company);
                $cost = $distance * $truck->truckType->fuel_cost_per_km;

                return [
                    'site' => $site,
                    'distance' => $distance,
                    'cost' => $cost,
                ];
            })
            ->sortBy('cost')
            ->first()['site'] ?? null;
    }

    /**
     * Choose best truck based on required capacity and efficiency
     */
    private function assignBestTruck($trucks, float $requiredCapacity): ?Truck
    {
        return $trucks
            ->filter(fn($truck) => $truck->co2_capacity >= min($requiredCapacity, 32)) // At least handle one trip
            ->sortBy(fn($truck) => $truck->truckType->fuel_cost_per_km)
            ->first();
    }

    /**
     * Calculate how many trips needed
     */
    private function calculateTripsNeeded(float $requiredCapacity, Truck $truck): int
    {
        return (int) ceil($requiredCapacity / $truck->co2_capacity);
    }

    /**
     * Get remaining weekly production capacity for a site
     */
    private function getRemainingWeeklyCapacity(ProductionSite $site, int $week, int $year): float
    {
        $allocated = Route::where('production_site_id', $site->id)
            ->where('week_number', $week)
            ->where('year', $year)
            ->whereIn('status', [RouteStatus::PENDING->value, RouteStatus::IN_PROGRESS->value])
            ->sum('co2_delivered');

        $weeklyProduction = $this->parseWeeklyProduction($site->weekly_production);

        return max(0, $weeklyProduction - $allocated);
    }

    /**
     * Get available buffer capacity at production site
     */
    private function getSiteAvailableBuffer(ProductionSite $site, int $week, int $year): float
    {
        $inBuffer = Route::where('production_site_id', $site->id)
            ->where('week_number', $week)
            ->where('year', $year)
            ->where('status', RouteStatus::PENDING->value)
            ->sum('co2_delivered');

        return max(0, $site->buffer_tank_size - $inBuffer);
    }

    /**
     * Get available buffer capacity at delivery company
     */
    private function getCompanyAvailableBuffer(DeliveryCompany $company, int $week, int $year): float
    {
        $inTransit = Route::where('delivery_company_id', $company->id)
            ->where('week_number', $week)
            ->where('year', $year)
            ->whereIn('status', [RouteStatus::IN_PROGRESS->value, RouteStatus::PENDING->value])
            ->sum('co2_delivered');

        return max(0, $company->buffer_tank_size - $inTransit);
    }

    /**
     * Estimate distance in km (handles multiple postcodes)
     */
    private function estimateDistance(ProductionSite $site, DeliveryCompany $company): float
    {
        $siteCoords = Cache::remember(
            "coords_{$site->location}",
            now()->addDays(30),
            fn() => $this->geo->geocodePostcode($site->location)
        );

        // Handle multiple postcodes (comma-separated)
        $companyPostcodes = array_map('trim', explode(',', $company->location));

        $distances = [];
        foreach ($companyPostcodes as $postcode) {
            $companyCoords = Cache::remember(
                "coords_{$postcode}",
                now()->addDays(30),
                fn() => $this->geo->geocodePostcode($postcode)
            );

            if ($siteCoords && $companyCoords) {
                $distances[] = $this->calculateDistance(
                    $siteCoords['lat'],
                    $siteCoords['lng'],
                    $companyCoords['lat'],
                    $companyCoords['lng']
                );
            }
        }

        // Return shortest distance if we have any, otherwise fallback
        return !empty($distances) ? min($distances) : rand(50, 500);
    }

    /**
     * Haversine formula - calculates distance between two lat/lng points in KM
     */
    private function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371; // km

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Calculate estimated route duration in minutes
     */
    private function calculateEstimatedDuration(float $distance): int
    {
        $avgSpeed = 60; // km/h average speed
        $travelTime = ($distance / $avgSpeed) * 60; // minutes

        $loadingTime = 30; // minutes
        $unloadingTime = 30; // minutes

        return (int) ceil($travelTime + $loadingTime + $unloadingTime);
    }

    /**
     * Parse weekly production value (handles "60-70" format or numeric values)
     */
    private function parseWeeklyProduction($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Handle range format "60-70" - use minimum value
        if (is_string($value) && str_contains($value, '-')) {
            $parts = explode('-', $value);
            return (float) trim($parts[0]);
        }

        return 0;
    }

    /**
     * Compare performance metrics between two weeks
     * Used to evaluate optimization improvements
     */
    public function compareWeeklyPerformance(int $week1, int $year1, int $week2, int $year2): array
    {
        $metrics1 = $this->getWeekMetrics($week1, $year1);
        $metrics2 = $this->getWeekMetrics($week2, $year2);

        return [
            'week_1' => ['week' => $week1, 'year' => $year1] + $metrics1,
            'week_2' => ['week' => $week2, 'year' => $year2] + $metrics2,
            'improvements' => [
                'total_distance_saved' => $metrics1['total_distance'] - $metrics2['total_distance'],
                'cost_saved' => $metrics1['total_cost'] - $metrics2['total_cost'],
                'emissions_reduced' => $metrics1['total_emissions'] - $metrics2['total_emissions'],
                'efficiency_gain_percent' => $this->calculateEfficiencyGain($metrics1, $metrics2),
            ],
        ];
    }

    /**
     * Get performance metrics for a specific week
     */
    public function getWeekMetrics(int $week, int $year): array
    {
        $routes = Route::where('week_number', $week)
            ->where('year', $year)
            ->get();

        if ($routes->isEmpty()) {
            return [
                'routes_count' => 0,
                'total_co2_delivered' => 0,
                'total_distance' => 0,
                'total_cost' => 0,
                'total_emissions' => 0,
                'avg_distance_per_tonne' => 0,
                'avg_cost_per_tonne' => 0,
                'trucks_used' => 0,
            ];
        }

        $totalCO2 = $routes->sum('co2_delivered');

        return [
            'routes_count' => $routes->count(),
            'total_co2_delivered' => $totalCO2,
            'total_distance' => $routes->sum('distance'),
            'total_cost' => $routes->sum('cost'),
            'total_emissions' => $routes->sum('emissions'),
            'avg_distance_per_tonne' => $totalCO2 > 0 ? round($routes->sum('distance') / $totalCO2, 2) : 0,
            'avg_cost_per_tonne' => $totalCO2 > 0 ? round($routes->sum('cost') / $totalCO2, 2) : 0,
            'trucks_used' => $routes->unique('truck_id')->count(),
        ];
    }

    /**
     * Calculate efficiency improvement percentage
     */
    private function calculateEfficiencyGain(array $metrics1, array $metrics2): float
    {
        if ($metrics1['avg_cost_per_tonne'] == 0) {
            return 0;
        }

        $improvement = (($metrics1['avg_cost_per_tonne'] - $metrics2['avg_cost_per_tonne'])
                / $metrics1['avg_cost_per_tonne']) * 100;

        return round($improvement, 2);
    }

    /**
     * Cache generated routes for a week (for quick retrieval)
     * Routes are cached for 7 days after generation
     */
    public function cacheWeekRoutes(int $week, int $year): bool
    {
        $cacheKey = "routes_week_{$week}_{$year}";

        $routes = Route::with(['truck.truckType', 'productionSite', 'deliveryCompany'])
            ->where('week_number', $week)
            ->where('year', $year)
            ->get();

        if ($routes->isEmpty()) {
            return false;
        }

        // Cache for 7 days
        Cache::put($cacheKey, $routes, now()->addDays(7));

        // Also cache summary metrics
        $summaryKey = "routes_summary_week_{$week}_{$year}";
        $summary = $this->getWeekMetrics($week, $year);
        Cache::put($summaryKey, $summary, now()->addDays(7));

        return true;
    }

    /**
     * Get cached routes for a week (or fetch from DB if not cached)
     */
    public function getCachedWeekRoutes(int $week, int $year)
    {
        $cacheKey = "routes_week_{$week}_{$year}";

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($week, $year) {
            return Route::with(['truck.truckType', 'productionSite', 'deliveryCompany'])
                ->where('week_number', $week)
                ->where('year', $year)
                ->get();
        });
    }

    /**
     * Clear route cache (use when routes are regenerated)
     */
    public function clearRouteCache(int $week, int $year): void
    {
        Cache::forget("routes_week_{$week}_{$year}");
        Cache::forget("routes_summary_week_{$week}_{$year}");
    }

    /**
     * Auto-complete routes from previous weeks
     * Releases trucks and buffer capacity for new week
     */
    private function autoCompletePreviousWeeks(int $currentWeek, int $currentYear): void
    {
        // Calculate the week before current
        $previousWeek = $currentWeek - 1;
        $previousYear = $currentYear;

        // Handle year boundary (week 1 should look at previous year's week 52)
        if ($previousWeek < 1) {
            $previousWeek = 52;
            $previousYear = $currentYear - 1;
        }

        // Complete all routes from weeks before current week
        Route::where(function($query) use ($currentWeek, $currentYear, $previousYear) {
            $query->where('year', '<', $currentYear)
                ->orWhere(function($q) use ($currentWeek, $currentYear) {
                    $q->where('year', $currentYear)
                        ->where('week_number', '<', $currentWeek);
                });
        })
            ->whereIn('status', [RouteStatus::PENDING->value, RouteStatus::IN_PROGRESS->value])
            ->get()
            ->each(function($route) {
                $route->complete();
            });
    }

    /**
     * Force regenerate routes for a week (deletes existing and creates new)
     */
    public function forceRegenerateWeek(int $week, int $year): array
    {
        // Delete existing routes for this week
        Route::where('week_number', $week)
            ->where('year', $year)
            ->delete();

        // Reset all trucks to available
        Truck::where('available_status', TruckStatus::IN_TRANSIT->value)
            ->update([
                'available_status' => TruckStatus::AVAILABLE->value,
                'production_site_id' => null,
                'delivery_company_id' => null,
            ]);

        // Clear cache
        $this->clearRouteCache($week, $year);

        // Generate fresh routes
        return $this->generateOptimisedRoutes($week, $year);
    }
}