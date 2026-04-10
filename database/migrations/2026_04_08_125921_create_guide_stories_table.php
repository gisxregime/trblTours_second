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
        if (Schema::hasTable('guide_stories')) {
            return;
        }

        Schema::create('guide_stories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guide_id')->index();
            $table->string('image_path');
            $table->string('caption', 280)->nullable();
            $table->text('content')->nullable();
            $table->json('image_paths')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->json('liked_by')->nullable();
            $table->json('messages')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['guide_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_stories');
    }
};
