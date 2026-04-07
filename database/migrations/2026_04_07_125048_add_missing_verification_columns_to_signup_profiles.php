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
        if (Schema::hasTable('tourists_profile')) {
            Schema::table('tourists_profile', function (Blueprint $table): void {
                if (! Schema::hasColumn('tourists_profile', 'id_front_verified')) {
                    $table->boolean('id_front_verified')->default(false);
                }

                if (! Schema::hasColumn('tourists_profile', 'id_back_verified')) {
                    $table->boolean('id_back_verified')->default(false);
                }

                if (! Schema::hasColumn('tourists_profile', 'selfie_verified')) {
                    $table->boolean('selfie_verified')->default(false);
                }
            });
        }

        if (Schema::hasTable('tour_guides_profile')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                if (! Schema::hasColumn('tour_guides_profile', 'id_front_verified')) {
                    $table->boolean('id_front_verified')->default(false);
                }

                if (! Schema::hasColumn('tour_guides_profile', 'id_back_verified')) {
                    $table->boolean('id_back_verified')->default(false);
                }

                if (! Schema::hasColumn('tour_guides_profile', 'selfie_verified')) {
                    $table->boolean('selfie_verified')->default(false);
                }

                if (! Schema::hasColumn('tour_guides_profile', 'nbi_clearance_validated')) {
                    $table->boolean('nbi_clearance_validated')->default(false);
                }

                if (! Schema::hasColumn('tour_guides_profile', 'approved_by_admin')) {
                    $table->boolean('approved_by_admin')->default(false);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tourists_profile')) {
            Schema::table('tourists_profile', function (Blueprint $table): void {
                $columns = ['id_front_verified', 'id_back_verified', 'selfie_verified'];
                $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tourists_profile', $column)));

                if ($existingColumns !== []) {
                    $table->dropColumn($existingColumns);
                }
            });
        }

        if (Schema::hasTable('tour_guides_profile')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                $columns = ['id_front_verified', 'id_back_verified', 'selfie_verified', 'nbi_clearance_validated', 'approved_by_admin'];
                $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tour_guides_profile', $column)));

                if ($existingColumns !== []) {
                    $table->dropColumn($existingColumns);
                }
            });
        }
    }
};
