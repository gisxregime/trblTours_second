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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'display_name')) {
                    $table->string('display_name')->nullable()->after('full_name');
                }
            });
        }

        if (Schema::hasTable('tour_guides_profile')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                if (! Schema::hasColumn('tour_guides_profile', 'city_municipality')) {
                    $table->string('city_municipality')->nullable()->after('nationality');
                }

                if (! Schema::hasColumn('tour_guides_profile', 'barangay')) {
                    $table->string('barangay')->nullable()->after('city_municipality');
                }

                if (! Schema::hasColumn('tour_guides_profile', 'profile_photo_path')) {
                    $table->string('profile_photo_path')->nullable()->after('selfie_path');
                }

                if (! Schema::hasColumn('tour_guides_profile', 'cover_photo_path')) {
                    $table->string('cover_photo_path')->nullable()->after('profile_photo_path');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'display_name')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('display_name');
            });
        }

        if (Schema::hasTable('tour_guides_profile')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                $columns = [
                    'city_municipality',
                    'barangay',
                    'profile_photo_path',
                    'cover_photo_path',
                ];

                $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tour_guides_profile', $column)));

                if ($existingColumns !== []) {
                    $table->dropColumn($existingColumns);
                }
            });
        }
    }
};
