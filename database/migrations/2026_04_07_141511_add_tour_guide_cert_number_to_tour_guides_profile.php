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
        if (! Schema::hasTable('tour_guides_profile') || Schema::hasColumn('tour_guides_profile', 'tour_guide_cert_number')) {
            return;
        }

        Schema::table('tour_guides_profile', function (Blueprint $table): void {
            $table->string('tour_guide_cert_number')->nullable()->after('selfie_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('tour_guides_profile') || ! Schema::hasColumn('tour_guides_profile', 'tour_guide_cert_number')) {
            return;
        }

        Schema::table('tour_guides_profile', function (Blueprint $table): void {
            $table->dropColumn('tour_guide_cert_number');
        });
    }
};
