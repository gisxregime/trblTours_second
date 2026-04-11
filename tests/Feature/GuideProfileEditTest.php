<?php

use App\Livewire\Guide\GuideProfile;
use App\Models\GuideStory;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('shows the guide profile edit form', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'name' => 'Maria Santos',
    ]);

    actingAs($user)
        ->get(route('dashboard.guide.profile.edit'))
        ->assertSuccessful()
        ->assertSee('Edit Guide Profile')
        ->assertSee('Display Name')
        ->assertSee('Profile Photo');
});

it('shows the facebook-style guide profile page', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'name' => 'Maria Santos',
        'display_name' => 'Guide Maria',
        'region' => 'Central Visayas',
    ]);

    DB::table('tour_guides_profile')->insert([
        'user_id' => $user->id,
        'phone_number' => '0912-345-6789',
        'nationality' => 'Filipino',
        'date_of_birth' => '1990-01-01',
        'years_of_experience' => 4,
        'bio' => str_repeat('Helping travelers discover local gems. ', 4),
        'government_id_type' => 'national_id',
        'government_id_number' => 'GUIDE-12345',
        'nbi_clearance_number' => 'NBI-12345',
        'city_municipality' => 'Cebu City',
        'barangay' => 'Lahug',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    actingAs($user)
        ->get(route('dashboard.guide.profile.show'))
        ->assertSuccessful()
        ->assertSee('Guide Maria')
        ->assertSee('Cebu City')
        ->assertSee('My Tours');
});

it('shows updated tour card values on the guide profile page', function () {
    $user = User::factory()->create([
        'role' => 'guide',
        'name' => 'Maria Santos',
        'display_name' => 'Guide Maria',
        'region' => 'Central Visayas',
    ]);

    DB::table('tour_guides_profile')->insert([
        'user_id' => $user->id,
        'phone_number' => '0912-345-6789',
        'nationality' => 'Filipino',
        'date_of_birth' => '1990-01-01',
        'years_of_experience' => 4,
        'bio' => str_repeat('Helping travelers discover local gems. ', 4),
        'government_id_type' => 'national_id',
        'government_id_number' => 'GUIDE-12345',
        'nbi_clearance_number' => 'NBI-12345',
        'city_municipality' => 'Cebu City',
        'barangay' => 'Lahug',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $tourPayload = [
        'guide_id' => $user->id,
        'created_by' => $user->id,
        'title' => 'Coron Lagoon Explorer',
        'name' => 'Coron Lagoon Explorer',
        'region' => 'Palawan',
        'city' => 'Coron',
        'summary' => 'A calm lagoon trip with island stops.',
        'description' => 'A calm lagoon trip with island stops.',
        'duration_label' => '4 hours',
        'duration_hours' => 4,
        'duration_unit' => 'hours',
        'min_guests' => 2,
        'max_guests' => 6,
        'price_per_person' => 2400,
        'price' => 2400,
        'price_unit' => 'person',
        'available_on' => '2026-04-18',
        'activities' => json_encode(['boat_bangka', 'walking_tour']),
        'status' => 'active',
    ];

    Tour::query()->create(
        collect($tourPayload)
            ->filter(fn (mixed $value, string $column): bool => Schema::hasColumn('tours', $column))
            ->all()
    );

    actingAs($user)
        ->get(route('dashboard.guide.profile.show'))
        ->assertSuccessful()
        ->assertSee('Coron Lagoon Explorer')
        ->assertSee('Coron')
        ->assertSee('4 hours')
        ->assertSee('2 - 6 guests')
        ->assertSee('Active')
        ->assertSee('Apr 18, 2026');
});

it('updates guide profile via ajax and returns json', function () {
    Storage::fake('public');

    $pngBinary = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO5+y9YAAAAASUVORK5CYII=');

    $user = User::factory()->create([
        'role' => 'tour_guide',
        'name' => 'Luis Rivera',
        'full_name' => 'Luis Rivera',
    ]);

    $payload = [
        'full_name' => 'Luis Rivera',
        'display_name' => 'Kuya Luis',
        'phone_number' => '0912-345-6789',
        'date_of_birth' => now()->subYears(25)->format('Y-m-d'),
        'region' => 'Central Visayas',
        'city_municipality' => 'Cebu City',
        'barangay' => 'Lahug',
        'bio' => str_repeat('Experienced local guide for island and cultural tours. ', 3),
        'profile_photo' => UploadedFile::fake()->createWithContent('profile.png', $pngBinary),
        'cover_photo' => UploadedFile::fake()->createWithContent('cover.png', $pngBinary),
    ];

    actingAs($user)
        ->patch(route('dashboard.guide.profile.update'), $payload, ['Accept' => 'application/json'])
        ->assertSuccessful()
        ->assertJson([
            'message' => 'Guide profile updated successfully.',
            'redirect_to' => route('dashboard.guide.profile.show'),
        ]);

    $user->refresh();

    expect($user->name)->toBe('Luis Rivera')
        ->and($user->display_name)->toBe('Kuya Luis')
        ->and($user->region)->toBe('Central Visayas')
        ->and($user->bio)->not->toBeNull();

    $profile = DB::table('tour_guides_profile')->where('user_id', $user->id)->first();

    expect($profile)->not->toBeNull()
        ->and($profile->phone_number)->toBe('0912-345-6789')
        ->and($profile->city_municipality)->toBe('Cebu City')
        ->and($profile->barangay)->toBe('Lahug')
        ->and($profile->profile_photo_path)->not->toBeNull();

    expect(Storage::disk('public')->exists($profile->profile_photo_path))->toBeTrue();
});

it('validates guide profile fields for ajax submit', function () {
    $user = User::factory()->create(['role' => 'guide']);

    actingAs($user)
        ->patch(route('dashboard.guide.profile.update'), [
            'full_name' => '',
            'display_name' => '',
            'phone_number' => '123456',
            'date_of_birth' => now()->subYears(10)->format('Y-m-d'),
            'region' => 'Invalid Region',
            'city_municipality' => '',
            'barangay' => '',
            'bio' => 'Short bio',
        ], ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'full_name',
            'display_name',
            'phone_number',
            'date_of_birth',
            'region',
            'city_municipality',
            'barangay',
            'bio',
        ]);
});

it('keeps existing photos when saving without new uploads', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'role' => 'guide',
        'name' => 'Mina Cruz',
        'full_name' => 'Mina Cruz',
    ]);

    Storage::disk('public')->put('guide/profile-photos/existing-profile.jpg', 'existing profile');
    Storage::disk('public')->put('guide/cover-photos/existing-cover.jpg', 'existing cover');

    DB::table('tour_guides_profile')->insert([
        'user_id' => $user->id,
        'phone_number' => '0912-345-6789',
        'nationality' => 'Filipino',
        'date_of_birth' => now()->subYears(30)->format('Y-m-d'),
        'years_of_experience' => 5,
        'bio' => str_repeat('Trusted local guide helping guests explore hidden gems safely. ', 3),
        'government_id_type' => 'national_id',
        'government_id_number' => 'GUIDE-555',
        'nbi_clearance_number' => 'NBI-555',
        'city_municipality' => 'Cebu City',
        'barangay' => 'Lahug',
        'profile_photo_path' => 'guide/profile-photos/existing-profile.jpg',
        'cover_photo_path' => 'guide/cover-photos/existing-cover.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    actingAs($user)
        ->patch(route('dashboard.guide.profile.update'), [
            'full_name' => 'Mina Cruz',
            'display_name' => 'Guide Mina',
            'phone_number' => '0912-345-6789',
            'date_of_birth' => now()->subYears(30)->format('Y-m-d'),
            'region' => 'Central Visayas',
            'city_municipality' => 'Cebu City',
            'barangay' => 'Lahug',
            'bio' => str_repeat('Trusted local guide helping guests explore hidden gems safely. ', 3),
        ], ['Accept' => 'application/json'])
        ->assertSuccessful();

    $profile = DB::table('tour_guides_profile')->where('user_id', $user->id)->first();

    expect($profile)->not->toBeNull()
        ->and($profile->profile_photo_path)->toBe('guide/profile-photos/existing-profile.jpg')
        ->and($profile->cover_photo_path)->toBe('guide/cover-photos/existing-cover.jpg');
});

it('creates a guide post with up to five previewable images', function () {
    Storage::fake('public');

    $pngBinary = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO5+y9YAAAAASUVORK5CYII=');

    $guide = User::factory()->create([
        'role' => 'tour_guide',
        'full_name' => 'Guide Poster',
        'display_name' => 'Guide Poster',
    ]);

    actingAs($guide);

    Livewire::test(GuideProfile::class)
        ->set('postText', 'Here is a beautiful view from my latest island tour.')
        ->set('postImages', [
            UploadedFile::fake()->createWithContent('view-1.png', $pngBinary),
            UploadedFile::fake()->createWithContent('view-2.png', $pngBinary),
            UploadedFile::fake()->createWithContent('view-3.png', $pngBinary),
            UploadedFile::fake()->createWithContent('view-4.png', $pngBinary),
            UploadedFile::fake()->createWithContent('view-5.png', $pngBinary),
        ])
        ->call('createPost')
        ->assertHasNoErrors();

    $post = GuideStory::query()->where('guide_id', $guide->id)->latest('id')->first();

    expect($post)->not->toBeNull()
        ->and((string) ($post->content ?? $post->caption ?? ''))->toContain('beautiful view')
        ->and(is_array($post->image_paths))->toBeTrue()
        ->and(count($post->image_paths ?? []))->toBe(5)
        ->and((int) ($post->likes_count ?? 0))->toBe(0);
});

it('toggles like and sends direct message on a guide post', function () {
    $guide = User::factory()->create([
        'role' => 'tour_guide',
        'full_name' => 'Guide Owner',
    ]);

    $post = GuideStory::query()->create([
        'guide_id' => $guide->id,
        'image_path' => 'guide/posts/seed.jpg',
        'image_paths' => ['guide/posts/seed.jpg'],
        'caption' => 'Seed post',
        'content' => 'Seed post',
        'likes_count' => 0,
        'liked_by' => [],
        'messages' => [],
        'expires_at' => now()->addYears(10),
    ]);

    actingAs($guide);

    Livewire::test(GuideProfile::class)
        ->call('toggleLike', $post->id)
        ->set('messageInputs.'.$post->id, 'I will reply to interested tourists right away.')
        ->call('sendMessage', $post->id)
        ->assertHasNoErrors();

    $post->refresh();

    expect((int) ($post->likes_count ?? 0))->toBe(1)
        ->and($post->liked_by)->toContain($guide->id)
        ->and(is_array($post->messages))->toBeTrue()
        ->and(count($post->messages ?? []))->toBe(1)
        ->and((string) ($post->messages[0]['content'] ?? ''))->toContain('interested tourists');
});

it('accepts multiple post images, previews them, removes one, and publishes after clicking post', function () {
    Storage::fake('public');

    $pngBinary = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO5+y9YAAAAASUVORK5CYII=');

    $guide = User::factory()->create([
        'role' => 'tour_guide',
        'full_name' => 'Guide Draft Tester',
        'display_name' => 'Guide Draft Tester',
    ]);

    actingAs($guide);

    $component = Livewire::test(GuideProfile::class)
        ->set('postImages', [
            UploadedFile::fake()->createWithContent('draft-1.png', $pngBinary),
            UploadedFile::fake()->createWithContent('draft-2.png', $pngBinary),
            UploadedFile::fake()->createWithContent('draft-3.png', $pngBinary),
        ]);

    $component->assertSet('postImages.0', $component->get('postImages.0'));

    $component
        ->call('removePostImage', 1)
        ->assertHasNoErrors();

    expect(count($component->get('postImages')))->toBe(2);

    $component
        ->set('postText', 'Published from draft flow')
        ->call('createPost')
        ->assertHasNoErrors();

    $published = GuideStory::query()->where('guide_id', $guide->id)->latest('id')->first();

    expect($published)->not->toBeNull()
        ->and((string) ($published->content ?? ''))->toContain('Published from draft flow')
        ->and(count($published->image_paths ?? []))->toBe(2);
});

it('cancels draft post and removes uploaded draft photos', function () {
    Storage::fake('public');

    $pngBinary = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO5+y9YAAAAASUVORK5CYII=');

    $guide = User::factory()->create([
        'role' => 'tour_guide',
        'full_name' => 'Guide Cancel Tester',
        'display_name' => 'Guide Cancel Tester',
    ]);

    actingAs($guide);

    $component = Livewire::test(GuideProfile::class)
        ->set('postImages', [
            UploadedFile::fake()->createWithContent('cancel-1.png', $pngBinary),
        ]);

    $component
        ->call('cancelPostDraft')
        ->assertHasNoErrors();

    expect($component->get('postText'))->toBe('')
        ->and($component->get('postImages'))->toBe([]);
});
