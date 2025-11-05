<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionSite>
 */
class ProductionSiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Example shutdown periods
        $shutdownExamples = [
            '6 weeks in July, 6 weeks over Christmas',
            '4 weeks in August, 3 weeks in December',
            '2 weeks in June, 4 weeks in December',
            '3 weeks in September, 3 weeks in January',
            'No scheduled shutdowns',
        ];

        // Example source types
        $types = [
            'Distillery',
            'Biogas - food waste feedstock',
            'Biogas - manure feedstock',
        ];

        // Example operating Status
        $status = [
            'Already operating',
            'Go Live on 01/12/2025',
            'Go Live on 01/08/2026',
            'Go Live on 30/08/2026',
        ];

        return [
            'co2_production_sources' => $this->faker->company,
            'location' => $this->faker->postcode,
            'type' => $this->faker->randomElement($types),
            'system_operating_status' => $this->faker->randomElement($status),
            'annual_production' => $this->faker->randomFloat(2, 2000, 20000),
            'weekly_production' => $this->faker->randomFloat(2, 50, 500),
            'shutdown_periods' => $this->faker->randomElement($shutdownExamples),
            'buffer_tank_size' => $this->faker->randomFloat(2, 20, 100),
        ];
    }
}
