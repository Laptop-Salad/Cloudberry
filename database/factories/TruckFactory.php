<?php

namespace Database\Factories;

use App\Models\TruckType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Truck>
 */
class TruckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'truck_plate' => strtoupper($this->faker->bothify('TPN-####')),
            'co2_capacity' => $this->faker->randomElement([20, 32]),
            'available_status' => $this->faker->randomElement(['available', 'in_use']),
            'truck_type_id' => TruckType::inRandomOrder()->first()?->id ?? TruckType::factory(),
        ];
    }
}
