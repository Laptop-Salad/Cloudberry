<?php

namespace Database\Factories;

use App\Enums\ConstraintType;
use App\Models\CreditCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryCompany>
 */
class DeliveryCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Only delivery related constraints
        $deliveryConstraints = [
            ConstraintType::ACCEPTS_CO2_FROM_CMA_FULLY_TESTED->value,
            ConstraintType::ACCEPTS_CO2_FROM_LL_FULLY_TESTED->value,
            ConstraintType::ACCEPTS_CO2_FROM_BIOGAS_NON_MANURE->value,
            ConstraintType::SEE_CREDIT_COMPANY_CONSTRAINTS->value,
        ];

        // Randomly pick 0–3 applicable constraints
        $selected = $this->faker->randomElements($deliveryConstraints, $this->faker->numberBetween(0, 3));

        $constraints = [];
        foreach ($selected as $value) {
            $constraints[$value] = true;
        }

        // Example source types
        $types = [
            'Food & Beverage User',
            'Carbonation',
        ];

        return [
            'co2_delivery_obligations' => $this->faker-> company,
            'location' => $this->faker->postcode,
            'type' => $this->faker->randomElement($types),
            'cod' =>  $this->faker->date,
            'annual_min_obligation' => $this->faker->randomFloat(2, 500, 5500),
            'annual_max_obligation' => $this->faker->randomFloat(2, 700, 10000),
            'weekly_min' => $this->faker->randomFloat(2, 10, 50),
            'weekly_max' => $this->faker->randomFloat(2, 15, 200),
            'buffer_tank_size' => $this->faker->randomFloat(2, 20, 50),
            'credit_company_id' => CreditCompany::inRandomOrder()->first()?->id ?? CreditCompany::factory(),
            'constraints' => $constraints,
        ];
    }
}
