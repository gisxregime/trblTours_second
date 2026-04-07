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
        if (! Schema::hasTable('tourists_profile')) {
            Schema::create('tourists_profile', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('phone_number');
                $table->string('nationality');
                $table->date('date_of_birth');
                $table->string('tourist_id_type');
                $table->string('tourist_id_number')->nullable();
                $table->string('id_front_path')->nullable();
                $table->string('id_back_path')->nullable();
                $table->string('selfie_path')->nullable();
                $table->boolean('id_front_verified')->default(false);
                $table->boolean('id_back_verified')->default(false);
                $table->boolean('selfie_verified')->default(false);
                $table->boolean('terms_agreed')->default(false);
                $table->boolean('identity_consent')->default(false);
                $table->boolean('pending_understood')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('tour_guides_profile')) {
            Schema::create('tour_guides_profile', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('phone_number');
                $table->string('nationality');
                $table->date('date_of_birth');
                $table->unsignedSmallInteger('years_of_experience');
                $table->text('bio');
                $table->string('government_id_type');
                $table->string('government_id_number');
                $table->string('id_front_path')->nullable();
                $table->string('id_back_path')->nullable();
                $table->string('selfie_path')->nullable();
                $table->string('tour_guide_cert_number')->nullable();
                $table->string('nbi_clearance_number');
                $table->string('nbi_clearance_path')->nullable();
                $table->string('barangay_clearance_number')->nullable();
                $table->string('barangay_clearance_path')->nullable();
                $table->boolean('nbi_clearance_validated')->default(false);
                $table->boolean('id_front_verified')->default(false);
                $table->boolean('id_back_verified')->default(false);
                $table->boolean('selfie_verified')->default(false);
                $table->boolean('approved_by_admin')->default(false);
                $table->boolean('terms_agreed')->default(false);
                $table->boolean('identity_consent')->default(false);
                $table->boolean('pending_understood')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_guides_profile');
        Schema::dropIfExists('tourists_profile');
    }
};
