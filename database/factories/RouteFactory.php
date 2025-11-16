<?php

namespace Database\Factories;

use App\Enums\RouteStatus;
use App\Models\CreditCompany;
use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Truck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Route>
 */
class RouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'production_site_id' => ProductionSite::inRandomOrder()->first()?->id ?? ProductionSite::factory(),
            'delivery_company_id' => DeliveryCompany::inRandomOrder()->first()?->id ?? DeliveryCompany::factory(),
            'credit_company'  => null,
            'truck_id' => Truck::inRandomOrder()->first()?->id ?? Truck::factory(),
            'distance' => $distance = fake()->randomFloat(2, 10, 400),
            'fuel_consumption' => $distance * fake()->randomFloat(2, 0.25, 0.36),
            'emissions' => $distance * fake()->randomFloat(2, 0.2, 0.8),
            'cost' => $distance * 1.43, // average fuel cost per km
            'co2_delivered' => $this->faker->randomFloat(2, 20, 32),
            'status' => $this->faker->randomElement(RouteStatus::cases())->value,
            'is_early_delivery' => false,
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
            'week_number' => $this->faker->numberBetween(1, 52),
            'year' => $this->faker->numberBetween(2025, 2026),
            'trip_number' => 1,
            'total_trips' => 1,
            'estimated_duration_minutes' => $this->faker->numberBetween(120, 480),
        ];
    }

    /**
     * Create a route with credit company
     */
    public function withCreditCompany(): static
    {
        return $this->state(fn (array $attributes) => [
            'credit_company_id' => CreditCompany::inRandomOrder()->first()?->id ?? CreditCompany::factory(),
            'is_early_delivery' => $this->faker->boolean(30), // 30% chance of early delivery
        ]);
    }

    /**
     * Create a multi-trip route
     */
    public function multiTrip(int $totalTrips = 3): static
    {
        return $this->state(fn (array $attributes) => [
            'trip_number' => $this->faker->numberBetween(1, $totalTrips),
            'total_trips' => $totalTrips,
        ]);
    }
}
