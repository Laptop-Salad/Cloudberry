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
        // Default, if someone uses factory without specifying state
        return [
            'capacity' => 20,
            'count_available' => 3,
            'fuel_consumption_per_km' => 0.30,
            'emission_factor' => 0.670,
            'fuel_cost_per_km' => 0.30 * 1.4257,
        ];
    }

    public function twentyTonne(): static
    {
        return $this->state(fn () => [
            'capacity' => 20,
            'count_available' => 3,
            'fuel_consumption_per_km' => 0.30,
            'emission_factor' => 0.670,
            'fuel_cost_per_km' => 0.30 * 1.4257,
        ]);
    }

    public function thirtyTwoTonne(): static
    {
        return $this->state(fn () => [
            'capacity' => 32,
            'count_available' => 2,
            'fuel_consumption_per_km' => 0.36,
            'emission_factor' => 0.667,
            'fuel_cost_per_km' => 0.36 * 1.4257,
        ]);
    }
}
