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
        Schema::create('guide_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('fiesta_surcharge', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->date('earning_date');
            $table->enum('payout_status', ['pending', 'processing', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['guide_id', 'earning_date']);
            $table->index(['guide_id', 'payout_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_earnings');
    }
};
