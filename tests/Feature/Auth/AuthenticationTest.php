<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('login screen can be rendered', function () {
    $response = get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    assertAuthenticated();
    $response->assertRedirect(route('dashboard.tourist', absolute: false));
});

test('users are redirected to their role dashboard even with unauthorized intended url', function () {
    $user = User::factory()->create(['role' => 'tourist']);

    get(route('dashboard.admin'))->assertRedirect(route('login', absolute: false));

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    assertAuthenticated();
    $response->assertRedirect(route('dashboard.tourist', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->post('/logout');

    assertGuest();
    $response->assertRedirect('/');
});
