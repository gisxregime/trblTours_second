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

        Schema::table('tour_guides_profile', function (Blueprint $table) {
            if (! Schema::hasColumn('tour_guides_profile', 'nbi_clearance_number')) {
                $table->string('nbi_clearance_number')->nullable()->after('bio');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'nbi_clearance_path')) {
                $table->string('nbi_clearance_path')->nullable()->after('nbi_clearance_number');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'barangay_clearance_number')) {
                $table->string('barangay_clearance_number')->nullable()->after('nbi_clearance_path');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'barangay_clearance_path')) {
                $table->string('barangay_clearance_path')->nullable()->after('barangay_clearance_number');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'nbi_clearance_validated')) {
                $table->boolean('nbi_clearance_validated')->default(false)->after('barangay_clearance_path');
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

        Schema::table('tour_guides_profile', function (Blueprint $table) {
            $columns = [
                'nbi_clearance_number',
                'nbi_clearance_path',
                'barangay_clearance_number',
                'barangay_clearance_path',
                'nbi_clearance_validated',
            ];

            $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tour_guides_profile', $column)));

            if ($existingColumns !== []) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
