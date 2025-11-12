<?php

namespace Database\Seeders;

use App\Enums\ConstraintType;
use App\Models\CreditCompany;
use App\Models\DeliveryCompany;
use Illuminate\Database\Seeder;

class DeliveryCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryCompany::truncate();

        $heading = true;
        $csv_path = fopen(base_path('/database/data/DeliveryCompanies.csv'), 'r');

        while (($line = fgetcsv($csv_path, 1000, ',')) !== false)
        {
            if(!$heading)
            {
                $constraintText = strtolower(trim($line[9] ?? ''));
                $constraints = [];

                if (str_contains($constraintText, 'credit')) {
                    $constraints['delivery_condition'] = ConstraintType::SEE_CREDIT_COMPANY_CONSTRAINTS->value;
                } elseif (str_contains($constraintText, 'cask')) {
                    $constraints['delivery_condition'] = ConstraintType::ACCEPTS_CO2_FROM_CMA_FULLY_TESTED->value;
                } elseif (str_contains($constraintText, 'loch')){
                    $constraints['delivery_condition'] = ConstraintType::ACCEPTS_CO2_FROM_LL_FULLY_TESTED->value;
                } elseif (str_contains($constraintText, 'biogas')) {
                    $constraints['delivery_condition'] = ConstraintType::ACCEPTS_CO2_FROM_BIOGAS_NON_MANURE->value;
                }else {
                    //Default constraint if no specific match found
                    $constraints['delivery_condition'] = ConstraintType::NONE->value;
                }

                $delivery_company = [
                    'name' => $line[0] ?? null,
                    'location' => $line[1] ?? null,
                    'type' => $line[2] ?? null,
                    'cod' => $line[3] ?? null,
                    'annual_min_obligation' => $line[4] ?? null,
                    'annual_max_obligation' => $line[5] ?? null,
                    'weekly_min' => $line[6] ?? null,
                    'weekly_max' => $line[7] ?? null,
                    'buffer_tank_size' => $line[8] ?? null,
                    'constraints'=> $constraints,
                ];

                $deliveryCompany = DeliveryCompany::create($delivery_company);
            }
            $heading = false;
        }
        fclose($csv_path);

        if (DeliveryCompany::count() === 0) {
            DeliveryCompany::factory(5)->create();
        }
    }
}
