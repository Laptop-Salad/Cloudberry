<?php

namespace Database\Seeders;

use App\Models\CreditCompanies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CreditCompanies::truncate();
        $heading = true;
        $csv_path = fopen(base_path('/database/data/CreditCompanies.csv'), 'r');
        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if(!$heading)
            {
                $credit_companies = array(
                    'cdr_credit_customer' => $line[0],
                    'credits_purchased' => $line[1],
                    'lca' => $line[2],
                    'co2_required' => $line[3],
                    'target_delivery_year' => $line[4],
                    'constraints_on_storage_method' => $line[5],
                    'constraints_on_co2_source' => $line[6],
                );
                CreditCompanies::create($credit_companies);
            }
            $heading = false;
        }
        fclose($csv_path);
    }
}
