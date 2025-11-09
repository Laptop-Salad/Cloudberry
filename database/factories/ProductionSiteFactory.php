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

        // Example of real UK postcodes
        $postcode = [
            'AB1 0BS', 'CM0 7AA', 'FK9 5BY', 'HG5 9LR',
            'IV9 8RT', 'KY7 7EZ', 'ME3 0AL',
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
            'name' => $this->faker->company,
            'location' => $this->faker->randomElement($postcode),
            'type' => $this->faker->randomElement($types),
            'system_operating_status' => $this->faker->randomElement($status),
            'annual_production' => $this->faker->randomFloat(2, 2000, 20000),
            'weekly_production' => $this->faker->randomFloat(2, 50, 500),
            'buffer_tank_size' => $this->faker->randomFloat(2, 20, 100),
        ];
    }
}
