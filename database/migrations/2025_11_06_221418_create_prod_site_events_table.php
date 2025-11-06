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
        Schema::create('prod_site_events', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        Schema::table('production_sites', function (Blueprint $table) {
            $table->dropColumn('shutdown_periods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_site_events');

        Schema::table('production_sites', function (Blueprint $table) {
            $table->string('shutdown_periods')->nullable();
        });
    }
};
