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
        Schema::create('zone_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius_km');
            
            $table->string('property_type')->nullable(); // Reference to PropertyType enum
            $table->unsignedBigInteger('min_price')->nullable(); // Stored in cents
            $table->unsignedBigInteger('max_price')->nullable(); // Stored in cents
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_alerts');
    }
};
