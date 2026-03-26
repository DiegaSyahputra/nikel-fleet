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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate', 20)->unique();
            $table->string('brand', 80);
            $table->string('model', 80);
            $table->smallInteger('year')->nullable();
            $table->enum('type', ['passenger', 'cargo']);
            $table->enum('ownership', ['owned', 'rented'])->default('owned');
            $table->enum('status', ['available', 'in_use', 'maintenance'])->default('available');
            $table->foreignId('region_id')->constrained()->restrictOnDelete();
            $table->string('color', 40)->nullable();
            $table->enum('fuel_type', ['bensin', 'solar', 'listrik'])->default('solar');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
