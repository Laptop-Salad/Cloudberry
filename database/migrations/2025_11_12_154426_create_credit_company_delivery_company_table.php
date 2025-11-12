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
        Schema::create('credit_company_delivery_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_company_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_company_delivery_company');
    }
};
