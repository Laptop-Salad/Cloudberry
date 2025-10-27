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
        Schema::create('production_sites', function (Blueprint $table) {
            $table->id();
            $table->string('co2_production_sources');
            $table->string('location');
            $table->string('type');
            $table->string('system_operating_status');
            $table->float('annual_production')->nullable();
            $table->float('weekly_production')->nullable();
            $table->string('shutdown_periods')->nullable();
            $table->float('buffer_tank_size')->nullable();
            $table->json('constraints')->default(json_encode([]));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_sites');
    }
};
