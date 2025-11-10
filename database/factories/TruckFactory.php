<?php

namespace Database\Factories;

use App\Enums\TruckStatus;
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
        $truckType = TruckType::inRandomOrder()->first() ?? TruckType::factory()->twentyTonne()->create();

        return [
            'truck_plate' => strtoupper($this->faker->bothify('TPN-####')),
            'truck_type_id' => $truckType->id,
            'co2_capacity' => $truckType->capacity,
            'available_status' => $this->faker->randomElement([
                TruckStatus::AVAILABLE->value,
                TruckStatus::IN_TRANSIT->value,
                ]),
        ];
    }
}
