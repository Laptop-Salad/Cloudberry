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
        Schema::table('delivery_companies', function (Blueprint $table) {
            $table->renameColumn('co2_delivery_obligations', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_companies', function (Blueprint $table) {
            $table->renameColumn('name', 'co2_delivery_obligations');
        });
    }
};
