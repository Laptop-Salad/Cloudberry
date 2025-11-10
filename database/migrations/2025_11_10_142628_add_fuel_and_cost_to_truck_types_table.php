<?php

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
        Schema::table('truck_types', function (Blueprint $table) {
            $table->float('fuel_consumption_per_km')->default(0);
            $table->float('emission_factor')->default(0);
            $table->decimal('fuel_cost_per_km', 10, 4)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('truck_types', function (Blueprint $table) {
            $table->dropColumn('fuel_consumption_per_km');
            $table->dropColumn('emission_factor');
            $table->dropColumn('fuel_cost_per_km');
        });
    }
};
