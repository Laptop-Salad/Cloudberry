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
        Schema::create('credit_companies', function (Blueprint $table) {
            $table->id();
            $table->string('cdr_credit_customer');
            $table->float('credits_purchased')->nullable();
            $table->float('lca')->nullable();
            $table->float('co2_required')->nullable();
            $table->float('target_delivery_year')->nullable();
            $table->json('constraints')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_companies');
    }
};
