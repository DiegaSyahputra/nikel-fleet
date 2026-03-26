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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 30)->unique();
            $table->foreignId('requester_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('driver_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('approver_l1_id');
            $table->unsignedBigInteger('approver_l2_id');
            $table->dateTime('departure_at');
            $table->dateTime('return_at');
            $table->string('destination', 255);
            $table->text('purpose');
            $table->tinyInteger('passengers')->unsigned()->default(1);
            $table->enum('status', [
                'draft', 'pending_l1', 'pending_l2', 'approved', 'rejected', 'cancelled'
            ])->default('pending_l1');
            $table->timestamps();

            $table->foreign('approver_l1_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('approver_l2_id')->references('id')->on('users')->restrictOnDelete();
            $table->index('status');
            $table->index('departure_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
