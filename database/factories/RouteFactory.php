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
            'distance' => $this->faker->randomFloat(2, 20, 1000),
            'emissions' => $this->faker->randomFloat(2, 500, 500),
            'co2_delivered' => $this->faker->randomFloat(2, 20, 32),
            'status' => $this->faker->randomElement(RouteStatus::cases())->value,
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'completed_at' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
        ];
    }
}
