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
        Schema::create('delivery_companies', function (Blueprint $table) {
            $table->id();
            $table->string('co2_delivery_obligations');
            $table->string('location');
            $table->string('type');
            $table->string('cod')->nullable();
            $table->float('annual_min_obligation')->nullable();
            $table->float('annual_max_obligation')->nullable();
            $table->float('weekly_min')->nullable();
            $table->float('weekly_max')->nullable();
            $table->float('buffer_tank_size')->nullable();
            $table->foreignId('credit_company_id')-> nullable()
                ->constrained('credit_companies')->nullOnDelete();
            $table->json('constraints')->default(json_encode([]));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_companies');
    }
};
