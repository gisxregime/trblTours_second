<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;

it('shows completion and verification prompts for guide dashboard', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
        'bio' => str_repeat('Guide bio ', 12),
        'specialty' => 'Island hopping',
    ]);

    DB::table('tour_guides_profile')->insert([
        'user_id' => $user->id,
        'phone_number' => '09171234567',
        'nationality' => 'Filipino',
        'date_of_birth' => '1990-01-01',
        'years_of_experience' => 3,
        'bio' => str_repeat('Story ', 25),
        'government_id_type' => 'national_id',
        'government_id_number' => 'GUIDE-12345',
        'id_front_path' => 'docs/id-front.jpg',
        'id_back_path' => null,
        'selfie_path' => 'docs/selfie.jpg',
        'nbi_clearance_number' => 'NBI-12345',
        'nbi_clearance_path' => 'docs/nbi.pdf',
        'barangay_clearance_number' => null,
        'barangay_clearance_path' => null,
        'nbi_clearance_validated' => false,
        'id_front_verified' => false,
        'id_back_verified' => false,
        'selfie_verified' => false,
        'approved_by_admin' => false,
        'terms_agreed' => true,
        'identity_consent' => true,
        'pending_understood' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    actingAs($user)
        ->get(route('dashboard.guide'))
        ->assertSuccessful()
        ->assertSee('Complete your profile to start accepting bookings!')
        ->assertSee('Your Tour Listings')
        ->assertSee('Recent Reviews')
        ->assertSeeText('Your documents are under review.');
});

it('allows tour_guide role to access guide dashboard', function () {
    $user = User::factory()->create([
        'role' => 'tour_guide',
        'full_name' => 'Luis Rivera',
    ]);

    actingAs($user)
        ->get(route('dashboard.guide'))
        ->assertSuccessful()
        ->assertSee('Your Tour Listings')
        ->assertSee('Recent Reviews');
});
