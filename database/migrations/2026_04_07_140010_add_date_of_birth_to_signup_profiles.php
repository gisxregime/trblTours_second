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
        if (Schema::hasTable('tourists_profile') && ! Schema::hasColumn('tourists_profile', 'date_of_birth')) {
            Schema::table('tourists_profile', function (Blueprint $table): void {
                $table->date('date_of_birth')->nullable();
            });
        }

        if (Schema::hasTable('tour_guides_profile') && ! Schema::hasColumn('tour_guides_profile', 'date_of_birth')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                $table->date('date_of_birth')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tourists_profile') && Schema::hasColumn('tourists_profile', 'date_of_birth')) {
            Schema::table('tourists_profile', function (Blueprint $table): void {
                $table->dropColumn('date_of_birth');
            });
        }

        if (Schema::hasTable('tour_guides_profile') && Schema::hasColumn('tour_guides_profile', 'date_of_birth')) {
            Schema::table('tour_guides_profile', function (Blueprint $table): void {
                $table->dropColumn('date_of_birth');
            });
        }
    }
};
