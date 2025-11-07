<?php

namespace Database\Seeders;

use App\Enums\ConstraintType;
use App\Models\CreditCompany;
use Carbon\Carbon;
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
                } else {
                    $constraints['storage_method'] = ConstraintType::NONE->value;
                }

                if (str_contains($sourceConstraint, 'distillery')) {
                    $constraints['co2_source'] = ConstraintType::MUST_BE_DISTILLERY_SOURCE->value;
                } else {
                    $constraints['co2_source'] = ConstraintType::NONE->value;
                }

                $target_delivery_year = null;

                if ($line[4]) {
                    $target_delivery_year = Carbon::createFromFormat('Y-m-d', intval($line[4]) . '-01-01');
                }

                $credit_company = [
                    'name' => $line[0] ?? null,
                    'credits_purchased' => $line[1] ?? null,
                    'lca' => $line[2] ?? null,
                    'co2_required' => $line[3] ?? null,
                    'target_delivery_year' => $target_delivery_year,
                    'constraints' => $constraints,
                ];
                CreditCompany::create($credit_company);
            }
            $heading = false;
        }
        fclose($csv_path);

        if (CreditCompany::count() === 0) {
            CreditCompany::factory(5)->create();
        }
    }
}
