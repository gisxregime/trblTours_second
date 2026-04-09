<?php

use App\Livewire\Guide\GuideAvailabilityManager;
use App\Models\GuideAvailability;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('shows the guide availability page', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user)
        ->get(route('dashboard.guide.availability'))
        ->assertSuccessful()
        ->assertSee('Guide Availability')
        ->assertSee('Add Availability');
});

it('allows guide to save availability entry', function () {
    $user = User::factory()->create([
        'role' => 'tour_guide',
        'full_name' => 'Luis Rivera',
    ]);

    actingAs($user);

    Livewire::test(GuideAvailabilityManager::class)
        ->set('form.date', now()->addDay()->format('Y-m-d'))
        ->set('form.status', 'limited_slots')
        ->set('form.note', 'Only two groups available due to local event')
        ->set('form.special_price', 2500)
        ->call('save')
        ->assertHasNoErrors();

    $entry = GuideAvailability::query()
        ->where('guide_id', $user->id)
        ->first();

    expect($entry)->not->toBeNull()
        ->and($entry?->status)->toBe('limited_slots')
        ->and((float) $entry?->special_price)->toBe(2500.0);
});

it('allows guide to delete availability entry', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Aira Cruz',
    ]);

    $entry = GuideAvailability::query()->create([
        'guide_id' => $user->id,
        'date' => now()->addDays(2)->format('Y-m-d'),
        'status' => 'available',
        'note' => 'Open for bookings',
        'special_price' => null,
    ]);

    actingAs($user);

    Livewire::test(GuideAvailabilityManager::class)
        ->call('delete', $entry->id)
        ->assertHasNoErrors();

    expect(GuideAvailability::query()->whereKey($entry->id)->exists())->toBeFalse();
});
