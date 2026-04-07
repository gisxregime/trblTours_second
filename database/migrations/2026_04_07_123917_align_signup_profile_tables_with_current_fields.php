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
                if (! Schema::hasColumn('tourists_profile', 'tourist_id_type')) {
                    $table->string('tourist_id_type')->default('passport');
                }

                if (! Schema::hasColumn('tourists_profile', 'tourist_id_number')) {
                    $table->string('tourist_id_number')->nullable();
                }

                if (! Schema::hasColumn('tourists_profile', 'id_front_path')) {
                    $table->string('id_front_path')->nullable();
                }

                if (! Schema::hasColumn('tourists_profile', 'id_back_path')) {
                    $table->string('id_back_path')->nullable();
                }

                if (! Schema::hasColumn('tourists_profile', 'selfie_path')) {
                    $table->string('selfie_path')->nullable();
                }

                if (! Schema::hasColumn('tourists_profile', 'terms_agreed')) {
                    $table->boolean('terms_agreed')->default(false);
                }

                if (! Schema::hasColumn('tourists_profile', 'identity_consent')) {
                    $table->boolean('identity_consent')->default(false);
                }

                if (! Schema::hasColumn('tourists_profile', 'pending_understood')) {
                    $table->boolean('pending_understood')->default(false);
                }

                if (! Schema::hasColumn('tourists_profile', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        if (Schema::hasTable('tour_guides_profile')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                if (! Schema::hasColumn('tour_guides_profile', 'nationality')) {
                    $table->string('nationality')->default('Filipino');
                }

                if (! Schema::hasColumn('tour_guides_profile', 'id_front_path')) {
                    $table->string('id_front_path')->nullable();
                }

                if (! Schema::hasColumn('tour_guides_profile', 'id_back_path')) {
                    $table->string('id_back_path')->nullable();
                }

                if (! Schema::hasColumn('tour_guides_profile', 'selfie_path')) {
                    $table->string('selfie_path')->nullable();
                }

                if (! Schema::hasColumn('tour_guides_profile', 'terms_agreed')) {
                    $table->boolean('terms_agreed')->default(false);
                }

                if (! Schema::hasColumn('tour_guides_profile', 'identity_consent')) {
                    $table->boolean('identity_consent')->default(false);
                }

                if (! Schema::hasColumn('tour_guides_profile', 'pending_understood')) {
                    $table->boolean('pending_understood')->default(false);
                }

                if (! Schema::hasColumn('tour_guides_profile', 'created_at')) {
                    $table->timestamps();
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
                $columns = [
                    'tourist_id_type',
                    'tourist_id_number',
                    'id_front_path',
                    'id_back_path',
                    'selfie_path',
                    'terms_agreed',
                    'identity_consent',
                    'pending_understood',
                    'created_at',
                    'updated_at',
                ];

                $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tourists_profile', $column)));

                if ($existingColumns !== []) {
                    $table->dropColumn($existingColumns);
                }
            });
        }

        if (Schema::hasTable('tour_guides_profile')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                $columns = [
                    'nationality',
                    'id_front_path',
                    'id_back_path',
                    'selfie_path',
                    'terms_agreed',
                    'identity_consent',
                    'pending_understood',
                    'created_at',
                    'updated_at',
                ];

                $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tour_guides_profile', $column)));

                if ($existingColumns !== []) {
                    $table->dropColumn($existingColumns);
                }
            });
        }
    }
};
