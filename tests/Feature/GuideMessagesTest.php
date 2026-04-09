<?php

use App\Livewire\Guide\GuideMessages;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Tour;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('shows the guide messages page', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Maria Santos',
    ]);

    actingAs($guide)
        ->get(route('dashboard.guide.messages'))
        ->assertSuccessful()
        ->assertSee('Guide Messages')
        ->assertSee('Conversations');
});

it('allows a guide to send a message in own conversation', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
        'full_name' => 'Luis Rivera',
    ]);

    $tourist = User::factory()->create();

    $tour = Tour::query()->create([
        'guide_id' => $guide->id,
        'title' => 'Sunset Harbor Walk',
        'region' => 'Central Visayas',
        'summary' => str_repeat('Scenic boardwalk with local stories and food stops. ', 3),
        'duration_label' => 'Half-day',
        'price_per_person' => 1999,
        'is_featured' => true,
        'available_on' => now()->addDays(6)->format('Y-m-d'),
    ]);

    $conversation = Conversation::query()->create([
        'tourist_id' => $tourist->id,
        'guide_id' => $guide->id,
        'tour_id' => $tour->id,
        'last_message_at' => now()->subMinute(),
    ]);

    Message::query()->create([
        'conversation_id' => $conversation->id,
        'sender_id' => $tourist->id,
        'message' => 'Hi guide, is this tour kid-friendly?',
        'is_read' => false,
    ]);

    actingAs($guide);

    Livewire::test(GuideMessages::class)
        ->set('selectedConversationId', $conversation->id)
        ->set('messageBody', 'Yes, it is family-friendly. We can adjust the pace.')
        ->call('sendMessage')
        ->assertHasNoErrors();

    $latest = Message::query()->where('conversation_id', $conversation->id)->latest('id')->first();

    expect($latest)->not->toBeNull()
        ->and($latest?->sender_id)->toBe($guide->id)
        ->and($latest?->message)->toContain('family-friendly');
});

it('prevents a guide from sending message to another guides conversation', function () {
    $guide = User::factory()->create(['role' => 'guide']);
    $otherGuide = User::factory()->create(['role' => 'guide']);
    $tourist = User::factory()->create();

    $tour = Tour::query()->create([
        'guide_id' => $otherGuide->id,
        'title' => 'Lagoon Paddle Tour',
        'region' => 'Palawan',
        'summary' => str_repeat('Paddle through mangrove lagoons with a local expert. ', 3),
        'duration_label' => 'Full-day',
        'price_per_person' => 2400,
        'is_featured' => true,
        'available_on' => now()->addDays(9)->format('Y-m-d'),
    ]);

    $foreignConversation = Conversation::query()->create([
        'tourist_id' => $tourist->id,
        'guide_id' => $otherGuide->id,
        'tour_id' => $tour->id,
    ]);

    actingAs($guide);

    Livewire::test(GuideMessages::class)
        ->set('selectedConversationId', $foreignConversation->id)
        ->set('messageBody', 'I should not be able to send this')
        ->call('sendMessage')
        ->assertHasNoErrors();

    expect(Message::query()->where('conversation_id', $foreignConversation->id)->exists())->toBeFalse();
});
