<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        if (! Schema::hasTable('tour_guides_profile')) {
            return;
        }

        Schema::table('tour_guides_profile', function (Blueprint $table): void {
            if (! Schema::hasColumn('tour_guides_profile', 'languages_spoken')) {
                $table->json('languages_spoken')->nullable()->after('barangay_clearance_number');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'specializations')) {
                $table->json('specializations')->nullable()->after('languages_spoken');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'government_id_path_front')) {
                $table->string('government_id_path_front')->nullable()->after('specializations');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'government_id_path_back')) {
                $table->string('government_id_path_back')->nullable()->after('government_id_path_front');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'nbi_clearance_path')) {
                $table->string('nbi_clearance_path')->nullable()->after('government_id_path_back');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'barangay_clearance_path')) {
                $table->string('barangay_clearance_path')->nullable()->after('nbi_clearance_path');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'tourism_certificates_path')) {
                $table->string('tourism_certificates_path')->nullable()->after('barangay_clearance_path');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable()->after('tourism_certificates_path');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('bank_account_name');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_name');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'gcash_number')) {
                $table->string('gcash_number')->nullable()->after('bank_account_number');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'id_verified')) {
                $table->boolean('id_verified')->default(false)->after('gcash_number');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'nbi_verified')) {
                $table->boolean('nbi_verified')->default(false)->after('id_verified');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'barangay_verified')) {
                $table->boolean('barangay_verified')->default(false)->after('nbi_verified');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'cert_verified')) {
                $table->boolean('cert_verified')->default(false)->after('barangay_verified');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('cert_verified');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approval_status');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('rejection_reason');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('verified_at');
            }

            if (! Schema::hasColumn('tour_guides_profile', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }

            $table->index('approval_status');
        });

        if (Schema::hasColumn('tour_guides_profile', 'id_front_path')) {
            DB::table('tour_guides_profile')
                ->whereNotNull('id_front_path')
                ->whereNull('government_id_path_front')
                ->update(['government_id_path_front' => DB::raw('id_front_path')]);
        }

        if (Schema::hasColumn('tour_guides_profile', 'id_back_path')) {
            DB::table('tour_guides_profile')
                ->whereNotNull('id_back_path')
                ->whereNull('government_id_path_back')
                ->update(['government_id_path_back' => DB::raw('id_back_path')]);
        }

        if (Schema::hasColumn('tour_guides_profile', 'nbi_clearance_file_path')) {
            DB::table('tour_guides_profile')
                ->whereNotNull('nbi_clearance_file_path')
                ->whereNull('nbi_clearance_path')
                ->update(['nbi_clearance_path' => DB::raw('nbi_clearance_file_path')]);
        }

        if (Schema::hasColumn('tour_guides_profile', 'barangay_clearance_file_path')) {
            DB::table('tour_guides_profile')
                ->whereNotNull('barangay_clearance_file_path')
                ->whereNull('barangay_clearance_path')
                ->update(['barangay_clearance_path' => DB::raw('barangay_clearance_file_path')]);
        }

        if (Schema::hasColumn('tour_guides_profile', 'certificates_file_paths')) {
            DB::table('tour_guides_profile')
                ->whereNotNull('certificates_file_paths')
                ->whereNull('tourism_certificates_path')
                ->update(['tourism_certificates_path' => DB::raw('certificates_file_paths')]);
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

        if (! Schema::hasTable('tour_guides_profile')) {
            return;
        }

        Schema::table('tour_guides_profile', function (Blueprint $table): void {
            if (Schema::hasColumn('tour_guides_profile', 'approval_status')) {
                $table->dropIndex(['approval_status']);
            }

            $columns = [
                'languages_spoken',
                'specializations',
                'government_id_path_front',
                'government_id_path_back',
                'nbi_clearance_path',
                'barangay_clearance_path',
                'tourism_certificates_path',
                'bank_account_name',
                'bank_name',
                'bank_account_number',
                'gcash_number',
                'id_verified',
                'nbi_verified',
                'barangay_verified',
                'cert_verified',
                'approval_status',
                'rejection_reason',
                'verified_at',
                'approved_at',
                'rejected_at',
            ];

            $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tour_guides_profile', $column)));
            if ($existingColumns !== []) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
