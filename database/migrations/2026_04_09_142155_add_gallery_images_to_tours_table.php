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
        if (! Schema::hasTable('tours')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table): void {
            if (! Schema::hasColumn('tours', 'featured_image')) {
                $table->string('featured_image')->nullable();
            }

            if (! Schema::hasColumn('tours', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('featured_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('tours')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table): void {
            $dropColumns = [];

            if (Schema::hasColumn('tours', 'gallery_images')) {
                $dropColumns[] = 'gallery_images';
            }

            if (Schema::hasColumn('tours', 'featured_image')) {
                $dropColumns[] = 'featured_image';
            }

            if ($dropColumns !== []) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
