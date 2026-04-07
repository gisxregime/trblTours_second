<?php

use Illuminate\Support\Facades\Schema;

test('signup database schema includes the required user and profile columns', function () {
    expect(Schema::hasColumn('users', 'full_name'))->toBeTrue();
    expect(Schema::hasColumn('users', 'status'))->toBeTrue();
    expect(Schema::hasColumn('users', 'phone_verified_at'))->toBeTrue();

    expect(Schema::hasTable('tourists_profile'))->toBeTrue();
    expect(Schema::hasColumn('tourists_profile', 'tourist_id_type'))->toBeTrue();
    expect(Schema::hasColumn('tourists_profile', 'id_front_path'))->toBeTrue();
    expect(Schema::hasColumn('tourists_profile', 'terms_agreed'))->toBeTrue();

    expect(Schema::hasTable('tour_guides_profile'))->toBeTrue();
    expect(Schema::hasColumn('tour_guides_profile', 'date_of_birth'))->toBeTrue();
    expect(Schema::hasColumn('tour_guides_profile', 'government_id_type'))->toBeTrue();
    expect(Schema::hasColumn('tour_guides_profile', 'government_id_number'))->toBeTrue();
    expect(Schema::hasColumn('tour_guides_profile', 'tour_guide_cert_number'))->toBeTrue();
    expect(Schema::hasColumn('tour_guides_profile', 'nbi_clearance_path'))->toBeTrue();
    expect(Schema::hasColumn('tour_guides_profile', 'barangay_clearance_path'))->toBeTrue();
    expect(Schema::hasColumn('tour_guides_profile', 'approved_by_admin'))->toBeTrue();
});
