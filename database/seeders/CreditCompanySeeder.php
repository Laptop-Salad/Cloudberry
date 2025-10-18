<?php

namespace Database\Seeders;

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
        $heading = true;
        $csv_path = fopen(base_path('/database/data/CreditCompanies.csv'), 'r');
        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if(!$heading)
            {
                $credit_company = array(
                    'cdr_credit_customer' => $line[0],
                    'credits_purchased' => $line[1],
                    'lca' => $line[2],
                    'co2_required' => $line[3],
                    'target_delivery_year' => $line[4],
                    'constraints_on_storage_method' => $line[5],
                    'constraints_on_co2_source' => $line[6],
                );
                CreditCompany::create($credit_company);
            }
            $heading = false;
        }
        fclose($csv_path);
    }
}
