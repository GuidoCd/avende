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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            $table->string('type'); // From PropertyType enum
            $table->string('status'); // From PropertyStatus enum
            
            $table->unsignedBigInteger('price'); // Stored in cents
            $table->string('currency')->default('USD');
            
            // Location
            $table->string('address');
            $table->string('city');
            $table->string('neighborhood')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            // Details
            $table->integer('surface_total')->nullable();
            $table->integer('surface_covered')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('garage')->nullable();
            
            // Flags and Metrics
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified_property')->default(false);
            $table->unsignedInteger('views_count')->default(0);

            $table->timestamps();
            
            // Spatial Indexes
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
