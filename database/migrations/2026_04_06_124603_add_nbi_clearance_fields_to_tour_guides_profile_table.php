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
        Schema::table('tour_guides_profile', function (Blueprint $table) {
            $table->string('nbi_clearance_number')->nullable()->after('bio');
            $table->string('nbi_clearance_path')->nullable()->after('nbi_clearance_number');
            $table->string('barangay_clearance_number')->nullable()->after('nbi_clearance_path');
            $table->string('barangay_clearance_path')->nullable()->after('barangay_clearance_number');
            $table->boolean('nbi_clearance_validated')->default(false)->after('barangay_clearance_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_guides_profile', function (Blueprint $table) {
            $table->dropColumn([
                'nbi_clearance_number',
                'nbi_clearance_path',
                'barangay_clearance_number',
                'barangay_clearance_path',
                'nbi_clearance_validated',
            ]);
        });
    }
};
