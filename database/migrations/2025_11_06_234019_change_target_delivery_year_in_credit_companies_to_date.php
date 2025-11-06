<?php

use App\Models\CreditCompany;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('credit_companies', function (Blueprint $table) {
            $table->date('target_delivery_year_date')->nullable()->after('target_delivery_year');
        });

        // all currently have del dates
        $credit_companies = CreditCompany::all();

        $credit_companies->each(function ($credit_company) {
           $credit_company->update([
               'target_delivery_year_date' => Carbon::create($credit_company->target_delivery_year, 1, 1),
           ]);

           $credit_company->save();
        });

        Schema::table('credit_companies', function (Blueprint $table) {
            $table->dropColumn('target_delivery_year');
        });

        Schema::table('credit_companies', function (Blueprint $table) {
            $table->renameColumn('target_delivery_year_date', 'target_delivery_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_companies', function (Blueprint $table) {
            //
        });
    }
};
