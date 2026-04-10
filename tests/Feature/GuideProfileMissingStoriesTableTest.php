<?php

use App\Models\Tour;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\actingAs;

it('renders the guide dashboard profile when guide stories table is missing', function () {
    Schema::dropIfExists('guide_stories');

    $guide = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Mika Reyes',
    ]);

    $guide->tourGuideProfile()->create([
        'phone_number' => '09123456789',
        'nationality' => 'Filipino',
        'date_of_birth' => '1990-01-01',
        'years_of_experience' => 5,
        'bio' => 'Local guide',
        'government_id_type' => 'Passport',
        'government_id_number' => 'AB123456',
        'nbi_clearance_number' => 'NBI123456',
        'approved_by_admin' => true,
    ]);

    actingAs($guide)
        ->get(route('dashboard.guide.profile.show'))
        ->assertSuccessful()
        ->assertSee('Guide Profile');
});

it('renders the guide dashboard profile when tour status column is missing', function () {
    Schema::shouldReceive('hasTable')
        ->with('tour_guides_profile')
        ->andReturnTrue();

    Schema::shouldReceive('hasTable')
        ->with('bookings')
        ->andReturnFalse();

    Schema::shouldReceive('hasTable')
        ->with('tour_reviews')
        ->andReturnFalse();

    Schema::shouldReceive('hasTable')
        ->with('guide_stories')
        ->andReturnFalse();

    Schema::shouldReceive('hasColumn')
        ->with((new Tour)->getTable(), 'status')
        ->andReturnFalse();

    $guide = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Mika Reyes',
    ]);

    $guide->tourGuideProfile()->create([
        'phone_number' => '09123456789',
        'nationality' => 'Filipino',
        'date_of_birth' => '1990-01-01',
        'years_of_experience' => 5,
        'bio' => 'Local guide',
        'government_id_type' => 'Passport',
        'government_id_number' => 'AB123456',
        'nbi_clearance_number' => 'NBI123456',
        'approved_by_admin' => true,
    ]);

    actingAs($guide)
        ->get(route('dashboard.guide.profile.show'))
        ->assertSuccessful()
        ->assertSee('Guide Profile');
});
