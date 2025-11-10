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
            // Ensure the column exists and is named correctly
            if (!Schema::hasColumn('routes', 'cost')) {
                $table->decimal('cost', 10, 2)->default(0)->after('emissions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            if (Schema::hasColumn('routes', 'cost')) {
                $table->dropColumn('cost');
            }
        });
    }
};
