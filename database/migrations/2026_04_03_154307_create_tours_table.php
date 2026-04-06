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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('region')->index();
            $table->text('summary')->nullable();
            $table->string('duration_label')->nullable();
            $table->decimal('price_per_person', 10, 2)->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(true)->index();
            $table->date('available_on')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
