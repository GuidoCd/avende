<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Publishing workflow
            $table->string('publishing_status')->default('draft')->after('is_active');
            
            // Convert enums to foreign IDs. Assuming type/status strings are being replaced.
            // Note: Since this is an alter, and if properties exist with string 'type' and 'status', 
            // replacing type column type could fail. If it fails, a specialized data migration is needed.
            // Since this is early development, dropping and replacing columns is usually safe or we migrate them later.
            $table->dropColumn(['type', 'status']);
            $table->foreignId('property_type_id')->nullable()->after('description')->constrained('property_types')->nullOnDelete();
            $table->foreignId('property_status_id')->nullable()->after('property_type_id')->constrained('property_statuses')->nullOnDelete();
            
            // Pricing additions
            $table->unsignedBigInteger('common_expenses')->nullable()->after('currency'); // Expensas
            $table->unsignedBigInteger('taxes')->nullable()->after('common_expenses');
            $table->string('rental_period')->nullable()->after('taxes');
            
            // Location additions
            $table->string('zip_code')->nullable()->after('neighborhood');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('street_number')->nullable()->after('address');
            
            // Dimension/Layout additions
            $table->integer('year_built')->nullable()->after('garage');
            $table->integer('half_bathrooms')->nullable()->after('bathrooms');
            $table->integer('floor_number')->nullable()->after('half_bathrooms');
            $table->integer('total_floors')->nullable()->after('floor_number');
            
            // Media additions
            $table->string('video_url')->nullable()->after('views_count');
            $table->string('virtual_tour_url')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['property_type_id']);
            $table->dropForeign(['property_status_id']);
            $table->dropColumn([
                'publishing_status',
                'property_type_id',
                'property_status_id',
                'common_expenses',
                'taxes',
                'rental_period',
                'zip_code',
                'state',
                'country',
                'street_number',
                'year_built',
                'half_bathrooms',
                'floor_number',
                'total_floors',
                'video_url',
                'virtual_tour_url'
            ]);
            
            // Re-add ancient columns
            $table->string('type')->nullable(); // Original Enums
            $table->string('status')->nullable();
        });
    }
};
