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
        Schema::create('booking_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->restrictOnDelete();
            $table->tinyInteger('level')->unsigned()->comment('1 atau 2');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_approvals');
    }
};
