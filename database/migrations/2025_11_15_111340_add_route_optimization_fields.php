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
            // Add week tracking
            $table->integer('week_number')->nullable()->after('status');
            $table->integer('year')->nullable()->after('week_number');

            // Add trip tracking for multi-trip deliveries
            $table->integer('trip_number')->default(1)->after('year');
            $table->integer('total_trips')->default(1)->after('trip_number');

            // Add estimated duration
            $table->integer('estimated_duration_minutes')->nullable()->after('total_trips');

            // Add index for common queries
            $table->index(['production_site_id', 'week_number', 'year']);
            $table->index(['delivery_company_id', 'week_number', 'year']);
            $table->index(['status', 'week_number', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropIndex(['production_site_id', 'week_number', 'year']);
            $table->dropIndex(['delivery_company_id', 'week_number', 'year']);
            $table->dropIndex(['status', 'week_number', 'year']);

            $table->dropColumn([
                'week_number',
                'year',
                'trip_number',
                'total_trips',
                'estimated_duration_minutes',
            ]);
        });
    }
};