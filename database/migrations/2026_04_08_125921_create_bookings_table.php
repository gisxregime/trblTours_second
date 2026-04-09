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
            $table->foreignId('booking_request_id')->unique()->constrained('booking_requests')->cascadeOnDelete();
            $table->foreignId('tourist_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('guide_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->date('booking_date');
            $table->unsignedInteger('group_size');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->enum('status', ['confirmed', 'in_progress', 'completed', 'cancelled'])->default('confirmed');
            $table->string('pickup_location')->nullable();
            $table->json('guest_names')->nullable();
            $table->text('special_notes')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['guide_id', 'booking_date']);
            $table->index(['tourist_id', 'booking_date']);
            $table->index(['status', 'booking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
