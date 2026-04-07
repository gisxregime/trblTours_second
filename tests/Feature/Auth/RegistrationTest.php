<?php

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('registration screen can be rendered', function () {
    $response = get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    assertAuthenticated();
    assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
        'full_name' => 'Test User',
        'role' => 'tourist',
        'status' => 'active',
    ]);
    $response->assertRedirect(route('dashboard.tourist', absolute: false));
});
