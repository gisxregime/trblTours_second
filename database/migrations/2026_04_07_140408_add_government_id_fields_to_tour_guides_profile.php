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
        if (! Schema::hasTable('tour_guides_profile')) {
            return;
        }

        Schema::table('tour_guides_profile', function (Blueprint $table): void {
            if (! Schema::hasColumn('tour_guides_profile', 'government_id_type')) {
                $table->string('government_id_type')->nullable()->default('national_id');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'government_id_number')) {
                $table->string('government_id_number')->nullable();
            }

            if (! Schema::hasColumn('tour_guides_profile', 'tour_guide_cert_number')) {
                $table->string('tour_guide_cert_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('tour_guides_profile')) {
            return;
        }

        Schema::table('tour_guides_profile', function (Blueprint $table): void {
            $columns = ['government_id_type', 'government_id_number', 'tour_guide_cert_number'];
            $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tour_guides_profile', $column)));

            if ($existingColumns !== []) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
