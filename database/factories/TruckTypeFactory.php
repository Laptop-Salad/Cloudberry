<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TruckType>
 */
class TruckTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly pick a truck size
        $capacity = $this->faker->randomElement([20, 32]);

        // Assign fuel/emission/cost based on truck size
        $truckData = [
            20 => [
                'fuel_consumption_per_km' => 0.30,
                'emission_factor' => 0.670,
            ],
            32 => [
                'fuel_consumption_per_km' => 0.36,
                'emission_factor' => 0.667,
            ],
        ];

        // UK diesel price Nov 2025
        $dieselPricePerLitre = 1.4257;

        return [
            'capacity' => $capacity,
            'count_available' => $this->faker->numberBetween(1,5),

            // Dynamic based on truck size
            'fuel_consumption_per_km' => $truckData[$capacity]['fuel_consumption_per_km'],
            'emission_factor' => $truckData[$capacity]['emission_factor'],

            // Cost = litres/km × price/litre
            'fuel_cost_per_km' => $truckData[$capacity]['fuel_consumption_per_km'] * $dieselPricePerLitre,
        ];
    }
}
