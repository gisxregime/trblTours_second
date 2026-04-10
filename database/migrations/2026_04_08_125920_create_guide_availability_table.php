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
        if (Schema::hasTable('guide_availability')) {
            return;
        }

        Schema::create('guide_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['available', 'fully_booked', 'fiesta', 'limited_slots'])->default('available');
            $table->text('note')->nullable();
            $table->decimal('special_price', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['guide_id', 'date']);
            $table->index(['status', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_availability');
    }
};
