<?php

namespace Database\Factories;

use App\Enums\ConstraintType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditCompany>
 */
class CreditCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Only credit related constraints
        $creditConstraints = [
            ConstraintType::MUST_BE_DISTILLERY_SOURCE->value,
            ConstraintType::MUST_BE_CARBONATION_OCO->value,
        ];

        // Randomly select 0–2 applicable constraints
        $selected = $this->faker->randomElements($creditConstraints, $this->faker->numberBetween(0, 2));

        $constraints = [];
        foreach ($selected as $value) {
            $constraints[$value] = true;
        }

        return [
            'cdr_credit_customer' => $this->faker->company,
            'credits_purchased' => $this->faker->randomFloat(2, 100, 5000),
            'lca' => $this->faker->randomFloat(2, 10, 95),
            'co2_required' => $this->faker->randomFloat(2, 100, 5000),
            'target_delivery_year' =>  $this->faker->year,
            'constraints' => $constraints,
        ];
    }
}
