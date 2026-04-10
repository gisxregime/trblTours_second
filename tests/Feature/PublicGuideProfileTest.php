<?php

use App\Models\GuideStory;
use App\Models\Tour;
use App\Models\User;

beforeEach(function () {
    // Create a guide user with tour guide profile
    $this->guide = User::factory()->create(['role' => 'guide']);

    // Create tour guide profile
    $this->guide->tourGuideProfile()->create([
        'phone_number' => '09123456789',
        'nationality' => 'Filipino',
        'date_of_birth' => '1990-01-01',
        'years_of_experience' => 8,
        'bio' => 'Passionate guide from Palawan',
        'government_id_type' => 'Passport',
        'government_id_number' => 'AB123456',
        'nbi_clearance_number' => 'NBI123456',
        'approved_by_admin' => true,
    ]);

    // Create some guide stories (posts)
    for ($i = 0; $i < 3; $i++) {
        GuideStory::factory()->create(['guide_id' => $this->guide->id]);
    }

    // Create some tours
    for ($i = 0; $i < 2; $i++) {
        Tour::factory()->create([
            'guide_id' => $this->guide->id,
            'status' => 'active',
        ]);
    }
});

test('public guide profile page is accessible', function () {
    $response = $this->get(route('guide.profile', $this->guide));

    $response->assertStatus(200);
    $response->assertViewIs('public.guide.profile');
});

test('profile page displays guide information', function () {
    $response = $this->get(route('guide.profile', $this->guide));

    $response->assertSeeText($this->guide->full_name);
    $response->assertSeeText('Verified');
});

test('profile page displays statistics', function () {
    $response = $this->get(route('guide.profile', $this->guide));

    $response->assertViewHas('stats');
    $this->assertArrayHasKey('totalToursCompleted', $response->viewData('stats'));
    $this->assertArrayHasKey('averageRating', $response->viewData('stats'));
    $this->assertArrayHasKey('totalReviews', $response->viewData('stats'));
});

test('profile page displays guide posts', function () {
    $response = $this->get(route('guide.profile', $this->guide));

    $response->assertViewHas('posts');
    $this->assertCount(3, $response->viewData('posts'));
});

test('profile page displays guide tours', function () {
    $response = $this->get(route('guide.profile', $this->guide));

    $response->assertViewHas('tours');
    $this->assertCount(2, $response->viewData('tours'));
});

test('non-guide user returns 404', function () {
    $tourist = User::factory()->create(['role' => 'tourist']);

    $response = $this->get(route('guide.profile', $tourist));

    $response->assertStatus(404);
});

test('profile page shows tour guide profile information', function () {
    $response = $this->get(route('guide.profile', $this->guide));

    $response->assertSeeText('8 years');
    $response->assertSeeText('Passionate guide from Palawan');
});
