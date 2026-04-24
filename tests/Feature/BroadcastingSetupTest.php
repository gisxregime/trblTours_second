<?php

use Illuminate\Support\Facades\Route;

it('registers the broadcasting authentication route', function (): void {
    $broadcastAuthRoute = collect(Route::getRoutes())->first(function ($route) {
        return $route->uri() === 'broadcasting/auth';
    });

    expect($broadcastAuthRoute)->not->toBeNull();
});
