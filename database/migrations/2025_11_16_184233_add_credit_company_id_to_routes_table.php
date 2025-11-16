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
        Schema::table('routes', function (Blueprint $table) {
            $table->foreignId('credit_company_id')
            ->nullable()
            ->constrained()
            ->onDelete('cascade');
            $table->boolean('is_early_delivery')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropForeign('credit_company_id');
            $table->dropColumn('credit_company_id');
        });
    }
};
