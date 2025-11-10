<?php

namespace Database\Factories;

use App\Enums\RouteStatus;
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
            'truck_id' => Truck::inRandomOrder()->first()?->id ?? Truck::factory(),
            'distance' => $distance = fake()->randomFloat(2, 10, 400),
            'fuel_consumption' => $distance * fake()->randomFloat(2, 0.25, 0.36),
            'emissions' => $distance * fake()->randomFloat(2, 0.2, 0.8),
            'cost' => $distance * 1.43, // average fuel cost per km
            'co2_delivered' => $this->faker->randomFloat(2, 20, 32),
            'status' => $this->faker->randomElement(RouteStatus::cases())->value,
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
        ];
    }
}
