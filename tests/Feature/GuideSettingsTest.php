<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\delete;
use function Pest\Laravel\from;

it('shows the guide settings page', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
    ]);

    actingAs($guide)
        ->get(route('dashboard.guide.settings'))
        ->assertSuccessful()
        ->assertSee('Guide Settings')
        ->assertSee('Change Password')
        ->assertSee('Delete Account');
});

it('updates guide password with strong password rules', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
    ]);

    actingAs($guide);

    $response = from(route('dashboard.guide.settings'))
        ->put(route('dashboard.guide.settings.password.update'), [
            'current_password' => 'password',
            'password' => 'StrongPass1',
            'password_confirmation' => 'StrongPass1',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard.guide.settings'));

    expect(Hash::check('StrongPass1', $guide->refresh()->password))->toBeTrue();
});

it('validates strong password requirements for guide settings', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
    ]);

    actingAs($guide);

    $response = from(route('dashboard.guide.settings'))
        ->put(route('dashboard.guide.settings.password.update'), [
            'current_password' => 'password',
            'password' => 'weakpass',
            'password_confirmation' => 'weakpass',
        ]);

    $response
        ->assertSessionHasErrorsIn('guidePassword', 'password')
        ->assertRedirect(route('dashboard.guide.settings'));
});

it('requires deletion confirmation checkbox before deleting guide account', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
    ]);

    actingAs($guide);

    $response = from(route('dashboard.guide.settings'))
        ->delete(route('dashboard.guide.settings.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasErrorsIn('guideDeletion', 'confirm_data_deletion')
        ->assertRedirect(route('dashboard.guide.settings'));

    expect($guide->fresh())->not->toBeNull();
});

it('deletes guide account after password and confirmation', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
    ]);

    actingAs($guide);

    $response = delete(route('dashboard.guide.settings.destroy'), [
        'password' => 'password',
        'confirm_data_deletion' => '1',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    assertGuest();
    expect($guide->fresh())->toBeNull();
});
