<?php

namespace Database\Seeders;

use App\Enums\ConstraintType;
use App\Models\CreditCompany;
use Illuminate\Database\Seeder;

class CreditCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CreditCompany::truncate();

        $csv_path = fopen(base_path('/database/data/CreditCompanies.csv'), 'r');
        $heading = true;

        while (($line = fgetcsv($csv_path, 1000, ',')) !== false)
        {
            if(!$heading)
            {
                $storageConstraint = strtolower(trim($line[5] ?? ''));
                $sourceConstraint = strtolower(trim($line[6] ?? ''));

                $constraints = [];

                if (str_contains($storageConstraint, 'carbonation - oco')) {
                    $constraints['storage_method'] = ConstraintType::MUST_BE_CARBONATION_OCO->value;
                } elseif ($storageConstraint && $storageConstraint !== 'none') {
                    $constraints['storage_method'] = ConstraintType::NONE->value;
                }

                if (str_contains($sourceConstraint, 'distillery')) {
                    $constraints['co2_source'] = ConstraintType::MUST_BE_DISTILLERY_SOURCE->value;
                } elseif ($sourceConstraint && $sourceConstraint !== 'none') {
                    $constraints['co2_source'] = ConstraintType::NONE->value;
                }

                $credit_company = [
                    'cdr_credit_customer' => $line[0] ?? null,
                    'credits_purchased' => $line[1] ?? null,
                    'lca' => $line[2] ?? null,
                    'co2_required' => $line[3] ?? null,
                    'target_delivery_year' => $line[4] ?? null,
                    'constraints' => $constraints,
                ];
                CreditCompany::create($credit_company);
            }
            $heading = false;
        }
        fclose($csv_path);
    }
}
