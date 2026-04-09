<?php

use App\Livewire\Guide\GuideTours;
use App\Models\Tour;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('shows the guide tours manager page', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user)
        ->get(route('dashboard.guide.tours'))
        ->assertSuccessful()
        ->assertSee('Guide Tours')
        ->assertSee('Create Tour');
});

it('allows a guide to create a tour from the livewire form', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user);

    Livewire::test(GuideTours::class)
        ->set('form.title', 'Sunset Harbor Walk')
        ->set('form.region', 'Central Visayas')
        ->set('form.city', 'Cebu City')
        ->set('form.category', 'Culture')
        ->set('form.summary', str_repeat('Walk the old harbor district with a local guide. ', 4))
        ->set('form.duration_label', 'Half-day')
        ->set('form.duration_hours', 4)
        ->set('form.price_per_person', 1999.50)
        ->set('form.available_on', now()->addWeek()->format('Y-m-d'))
        ->set('form.is_featured', true)
        ->set('form.status', 'active')
        ->call('save')
        ->assertHasNoErrors();

    $tour = Tour::query()->where('title', 'Sunset Harbor Walk')->first();

    expect($tour)->not->toBeNull()
        ->and($tour?->guide_id ?? $tour?->created_by)->toBe($user->id)
        ->and($tour?->region)->toBe('Central Visayas')
        ->and((float) ($tour?->price_per_person ?? $tour?->price))->toBe(1999.50);
});

it('allows a guide to delete an existing tour', function () {
    $user = User::factory()->create([
        'role' => 'tour_guide',
        'full_name' => 'Luis Rivera',
    ]);

    $tour = Tour::query()->create([
        'guide_id' => $user->id,
        'title' => 'Lagoon Paddle Tour',
        'region' => 'Palawan',
        'summary' => str_repeat('Paddle through calm waters with a local guide. ', 3),
        'duration_label' => 'Full-day',
        'price_per_person' => 2399.00,
        'is_featured' => true,
        'available_on' => now()->addDays(3)->format('Y-m-d'),
    ]);

    actingAs($user);

    Livewire::test(GuideTours::class)
        ->call('delete', $tour->id)
        ->assertHasNoErrors();

    expect(Tour::query()->whereKey($tour->id)->exists())->toBeFalse();
});
