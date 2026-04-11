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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'notification_email')) {
                $table->boolean('notification_email')->default(true)->after('email_verified_at');
            }

            if (! Schema::hasColumn('users', 'notification_sms')) {
                $table->boolean('notification_sms')->default(false)->after('notification_email');
            }

            if (! Schema::hasColumn('users', 'language_preference')) {
                $table->string('language_preference', 8)->default('en')->after('notification_sms');
            }

            if (! Schema::hasColumn('users', 'profile_public')) {
                $table->boolean('profile_public')->default(true)->after('language_preference');
            }

            if (! Schema::hasColumn('users', 'show_email_public')) {
                $table->boolean('show_email_public')->default(false)->after('profile_public');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'notification_email',
                'notification_sms',
                'language_preference',
                'profile_public',
                'show_email_public',
            ];

            $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('users', $column)));

            if ($existingColumns !== []) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
