<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('hidden admin login screen can be rendered', function () {
    $response = get(route('admin.login'));

    $response->assertSuccessful();
});

test('admin can authenticate through hidden admin login', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    $response = post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    assertAuthenticatedAs($admin);
    $response->assertRedirect(route('dashboard.admin', absolute: false));
});

test('non-admin users are blocked from hidden admin login', function () {
    $tourist = User::factory()->create([
        'role' => 'tourist',
    ]);

    $response = post(route('admin.login.store'), [
        'email' => $tourist->email,
        'password' => 'password',
    ]);

    assertGuest();
    $response->assertSessionHasErrors('email');
});

test('hidden admin login can be opened from a non-admin session', function () {
    $tourist = User::factory()->create(['role' => 'tourist']);

    $response = actingAs($tourist)->get(route('admin.login'));

    $response->assertSuccessful();
    assertGuest();
});
