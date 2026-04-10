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
        if (! Schema::hasTable('guide_stories')) {
            return;
        }

        Schema::table('guide_stories', function (Blueprint $table) {
            if (! Schema::hasColumn('guide_stories', 'content')) {
                $table->text('content')->nullable()->after('caption');
            }

            if (! Schema::hasColumn('guide_stories', 'image_paths')) {
                $table->json('image_paths')->nullable()->after('image_path');
            }

            if (! Schema::hasColumn('guide_stories', 'likes_count')) {
                $table->unsignedInteger('likes_count')->default(0)->after('content');
            }

            if (! Schema::hasColumn('guide_stories', 'liked_by')) {
                $table->json('liked_by')->nullable()->after('likes_count');
            }

            if (! Schema::hasColumn('guide_stories', 'messages')) {
                $table->json('messages')->nullable()->after('liked_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('guide_stories')) {
            return;
        }

        Schema::table('guide_stories', function (Blueprint $table) {
            $columnsToDrop = collect(['messages', 'liked_by', 'likes_count', 'image_paths', 'content'])
                ->filter(fn (string $column): bool => Schema::hasColumn('guide_stories', $column))
                ->values()
                ->all();

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
