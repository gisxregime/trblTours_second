<?php

use App\Models\BookingRequest;
use App\Models\Tour;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows tour listings and request posts on the tourist feed', function () {
    $tourist = User::factory()->create([
        'name' => 'Tourist User',
        'role' => 'tourist',
        'status' => 'active',
    ]);

    $guide = User::factory()->guide()->create([
        'name' => 'Verified Guide',
        'status' => 'active',
    ]);

    $tour = Tour::factory()->create([
        'guide_id' => $guide->id,
        'title' => 'Bohol Countryside Adventure',
        'region' => 'Central Visayas',
        'duration_label' => 'Full-day',
        'price_per_person' => 2500,
    ]);

    BookingRequest::query()->create([
        'tourist_id' => $tourist->id,
        'guide_id' => $guide->id,
        'tour_id' => $tour->id,
        'requested_date' => now()->addWeek()->toDateString(),
        'group_size' => 4,
        'total_price' => 9800,
        'special_requests' => 'Need child-friendly itinerary with flexible lunch stop.',
        'status' => 'pending',
    ]);

    actingAs($tourist)
        ->get(route('dashboard.tourist'))
        ->assertSuccessful()
        ->assertSee('Create Request Post')
        ->assertSee('Bohol Countryside Adventure')
        ->assertSee('Book Now')
        ->assertSee('View Details');
});

it('filters the tourist feed by post type', function () {
    $tourist = User::factory()->create([
        'role' => 'tourist',
        'status' => 'active',
    ]);

    $guide = User::factory()->guide()->create(['status' => 'active']);

    $tour = Tour::factory()->create([
        'guide_id' => $guide->id,
        'title' => 'Siargao Surf Escape',
        'region' => 'Caraga',
    ]);

    BookingRequest::query()->create([
        'tourist_id' => $tourist->id,
        'guide_id' => $guide->id,
        'tour_id' => $tour->id,
        'requested_date' => now()->addDays(10)->toDateString(),
        'group_size' => 3,
        'total_price' => 7600,
        'special_requests' => 'Sunrise activity preferred.',
        'status' => 'pending',
    ]);

    actingAs($tourist)
        ->get(route('dashboard.tourist', ['post_type' => 'tour_listings']))
        ->assertSuccessful()
        ->assertSee('Book Now')
        ->assertDontSee('View Details');

    actingAs($tourist)
        ->get(route('dashboard.tourist', ['post_type' => 'request_posts']))
        ->assertSuccessful()
        ->assertSee('View Details');
});
