<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/tourist', [DashboardController::class, 'tourist'])->name('dashboard.tourist');
    Route::post('/feed/filter', [FeedController::class, 'filter'])->name('feed.filter');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Nav links
    Route::get('/dashboard/my-posts', fn () => redirect()->route('dashboard.tourist', ['post_type' => 'request_posts']))->name('dashboard.my-posts');
    Route::get('/dashboard/my-bookings', fn () => view('dashboard.my-bookings'))->name('dashboard.my-bookings'); // Placeholder
    Route::get('/dashboard/messages', fn () => view('dashboard.messages'))->name('dashboard.messages'); // Placeholder

    Route::get('/dashboard/guide', [DashboardController::class, 'guide'])->name('dashboard.guide');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
