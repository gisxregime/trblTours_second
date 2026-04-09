<?php

use App\Livewire\Guide\GuideTours;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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
    Storage::fake('public');

    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user);

    Livewire::test(GuideTours::class)
        ->set('form.title', 'Sunset Harbor Walk')
        ->set('form.region', 'Central Visayas')
        ->set('form.city', 'Cebu City')
        ->set('form.summary', str_repeat('Walk the old harbor district with a local guide. ', 4))
        ->set('form.duration_hours', 4)
        ->set('form.duration_unit', 'hours')
        ->set('form.transportation', ['walking_tour', 'public_transportation'])
        ->set('form.min_guests', 2)
        ->set('form.max_guests', 8)
        ->set('form.price_per_person', 1999.50)
        ->set('form.currency', 'PHP')
        ->set('form.price_unit', 'person')
        ->set('form.available_on', now()->addWeek()->format('Y-m-d'))
        ->set('form.is_featured', true)
        ->set('form.status', 'active')
        ->set('tourPhotos', [
            UploadedFile::fake()->create('tour-1.jpg', 200, 'image/jpeg'),
            UploadedFile::fake()->create('tour-2.jpg', 200, 'image/jpeg'),
            UploadedFile::fake()->create('tour-3.jpg', 200, 'image/jpeg'),
        ])
        ->call('save')
        ->assertHasNoErrors();

    $tour = Tour::query()->where('title', 'Sunset Harbor Walk')->first();

    expect($tour)->not->toBeNull()
        ->and($tour?->guide_id ?? $tour?->created_by)->toBe($user->id)
        ->and($tour?->region)->toBe('Central Visayas')
        ->and((int) ($tour?->duration_hours ?? 0))->toBe(4)
        ->and($tour?->duration_unit)->toBe('hours')
        ->and(is_array($tour?->gallery_images))->toBeTrue()
        ->and(count((array) $tour?->gallery_images))->toBe(3)
        ->and((float) ($tour?->price_per_person ?? $tour?->price))->toBe(1999.50);

    if (Schema::hasColumn('tours', 'min_guests')) {
        expect((int) ($tour?->min_guests ?? 0))->toBe(2);
    }

    if (Schema::hasColumn('tours', 'max_guests')) {
        expect((int) ($tour?->max_guests ?? 0))->toBe(8);
    }

    foreach ((array) $tour?->gallery_images as $imagePath) {
        expect(Storage::disk('public')->exists($imagePath))->toBeTrue();
    }
});

it('keeps only the first three tour photos when more are selected', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user);

    Livewire::test(GuideTours::class)
        ->set('form.title', 'Canal Discovery Tour')
        ->set('form.region', 'National Capital Region')
        ->set('form.summary', str_repeat('Discover hidden canals and stories of old Manila. ', 3))
        ->set('form.duration_hours', 2)
        ->set('form.duration_unit', 'hours')
        ->set('form.price_per_person', 1500)
        ->set('form.currency', 'PHP')
        ->set('form.price_unit', 'person')
        ->set('form.status', 'draft')
        ->set('tourPhotos', [
            UploadedFile::fake()->create('tour-1.jpg', 200, 'image/jpeg'),
            UploadedFile::fake()->create('tour-2.jpg', 200, 'image/jpeg'),
            UploadedFile::fake()->create('tour-3.jpg', 200, 'image/jpeg'),
            UploadedFile::fake()->create('tour-4.jpg', 200, 'image/jpeg'),
        ])
        ->call('save')
        ->assertHasNoErrors();

    $tour = Tour::query()->where('title', 'Canal Discovery Tour')->first();

    expect($tour)->not->toBeNull()
        ->and(is_array($tour?->gallery_images))->toBeTrue()
        ->and(count((array) $tour?->gallery_images))->toBe(3);

    foreach ((array) $tour?->gallery_images as $imagePath) {
        expect(Storage::disk('public')->exists($imagePath))->toBeTrue();
    }
});

it('accumulates photos across upload events up to three', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user);

    Livewire::test(GuideTours::class)
        ->set('tourPhotos', [UploadedFile::fake()->create('tour-1.jpg', 200, 'image/jpeg')])
        ->call('processTourPhotos')
        ->set('tourPhotos', [UploadedFile::fake()->create('tour-2.jpg', 200, 'image/jpeg')])
        ->call('processTourPhotos')
        ->set('tourPhotos', [UploadedFile::fake()->create('tour-3.jpg', 200, 'image/jpeg')])
        ->call('processTourPhotos')
        ->assertHasNoErrors();

    $tour = Tour::query()->latest('id')->first();

    expect($tour)->not->toBeNull()
        ->and(is_array($tour?->gallery_images))->toBeTrue()
        ->and(count((array) $tour?->gallery_images))->toBe(3);
});

it('pre-fills form fields with latest draft data when editing', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user);

    $component = Livewire::test(GuideTours::class)
        ->set('form.title', 'Draft Manila River Walk')
        ->set('form.region', 'NCR')
        ->set('form.city', 'Manila')
        ->set('form.summary', 'A test draft summary.')
        ->set('form.duration_hours', 2)
        ->set('form.duration_unit', 'hours')
        ->set('form.price_per_person', 1200)
        ->assertHasNoErrors();

    $tour = Tour::query()->latest('id')->first();

    expect($tour)->not->toBeNull();

    $component
        ->call('edit', $tour->id)
        ->assertSet('editingTourId', $tour->id)
        ->assertSet('form.title', 'Draft Manila River Walk')
        ->assertSet('form.region', 'NCR')
        ->assertSet('form.city', 'Manila')
        ->assertSet('form.summary', 'A test draft summary.')
        ->assertSet('form.duration_hours', '2')
        ->assertSet('form.duration_unit', 'hours')
        ->assertSet('form.price_per_person', '1200.00');
});

it('redirects to guide dashboard after updating a tour', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    $tourPayload = [
        'guide_id' => $user->id,
        'created_by' => $user->id,
        'title' => 'Tour To Culling Game',
        'name' => 'Tour To Culling Game',
        'region' => 'Tokyo',
        'summary' => 'Initial summary.',
        'description' => 'Initial summary.',
        'duration' => '2 hours',
        'duration_label' => '2 hours',
        'duration_hours' => 2,
        'duration_unit' => 'hours',
        'price_per_person' => 3232,
        'price' => 3232,
        'status' => 'draft',
    ];

    $tour = Tour::query()->create(
        collect($tourPayload)
            ->filter(fn (mixed $value, string $column): bool => Schema::hasColumn('tours', $column))
            ->all()
    );

    actingAs($user);

    Livewire::test(GuideTours::class)
        ->call('edit', $tour->id)
        ->set('form.summary', 'Updated summary text.')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard.guide'));
});

it('allows a guide to replace a wrong uploaded photo', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($user);

    $component = Livewire::test(GuideTours::class)
        ->set('tourPhotos', [UploadedFile::fake()->create('wrong-photo.jpg', 200, 'image/jpeg')])
        ->call('processTourPhotos')
        ->assertHasNoErrors();

    $tour = Tour::query()->latest('id')->first();

    expect($tour)->not->toBeNull()
        ->and(is_array($tour?->gallery_images))->toBeTrue()
        ->and(count((array) $tour?->gallery_images))->toBe(1);

    $wrongImagePath = (string) $tour->gallery_images[0];

    $component
        ->call('removeTourPhoto', 0)
        ->set('tourPhotos', [UploadedFile::fake()->create('correct-photo.jpg', 200, 'image/jpeg')])
        ->call('processTourPhotos')
        ->assertHasNoErrors();

    $updatedTour = $tour->fresh();

    expect($updatedTour)->not->toBeNull()
        ->and(is_array($updatedTour?->gallery_images))->toBeTrue()
        ->and(count((array) $updatedTour?->gallery_images))->toBe(1)
        ->and($updatedTour?->gallery_images[0])->not->toBe($wrongImagePath);

    expect(Storage::disk('public')->exists($wrongImagePath))->toBeFalse();
    expect(Storage::disk('public')->exists((string) $updatedTour->gallery_images[0]))->toBeTrue();
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
