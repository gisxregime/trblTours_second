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
        if (! Schema::hasTable('tours') || Schema::hasColumn('tours', 'available_on')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table) {
            $table->date('available_on')->nullable()->after('admin_approved');
            $table->index('available_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('tours') || ! Schema::hasColumn('tours', 'available_on')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table) {
            $table->dropIndex(['available_on']);
            $table->dropColumn('available_on');
        });
    }
};
