<?php

use App\Enums\TruckStatus;
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
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->string('truck_plate')->unique();
            $table->float('co2_capacity');
            $table->unsignedTinyInteger('available_status')->default(TruckStatus::AVAILABLE->value);
            $table->foreignId('truck_type_id')-> nullable()
                ->constrained('truck_types')->nullOnDelete();
            $table->foreignId('production_site_id')-> nullable()->constrained()->nullOnDelete();
            $table->foreignId('delivery_company_id')-> nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
