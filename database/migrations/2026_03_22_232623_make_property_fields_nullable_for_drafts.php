<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            // Slug unique constraint still ok with nulls in modern MySQL/Postgres
            $table->string('slug')->nullable()->change();
            $table->unsignedBigInteger('price')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Reverting this is risky if data was added, but for completeness:
        Schema::table('properties', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->string('slug')->nullable(false)->change();
            $table->unsignedBigInteger('price')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->decimal('latitude', 10, 8)->nullable(false)->change();
            $table->decimal('longitude', 11, 8)->nullable(false)->change();
        });
    }
};
