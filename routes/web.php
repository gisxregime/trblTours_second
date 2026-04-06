<?php

use App\Models\Tour;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function (Request $request) {
    $regions = [
        'National Capital Region',
        'Cordillera Administrative Region',
        'Ilocos Region',
        'Cagayan Valley',
        'Central Luzon',
        'Calabarzon',
        'Mimaropa',
        'Bicol Region',
        'Western Visayas',
        'Central Visayas',
        'Eastern Visayas',
        'Zamboanga Peninsula',
        'Northern Mindanao',
        'Davao Region',
        'Soccsksargen',
        'Caraga',
        'Bangsamoro Autonomous Region in Muslim Mindanao',
    ];

    $query = trim((string) $request->string('q')->toString());
    $selectedRegion = $request->string('region')->toString();
    $selectedDate = $request->string('date')->toString();

    $tours = collect();

    if (Schema::hasTable('tours')) {
        $tourQuery = Tour::query()
            ->with('guide:id,full_name')
            ->where('is_featured', true)
            ->when($query !== '', function ($builder) use ($query): void {
                $builder->where(function ($search) use ($query): void {
                    $search->where('name', 'like', '%'.$query.'%')
                        ->orWhere('description', 'like', '%'.$query.'%')
                        ->orWhere('region', 'like', '%'.$query.'%')
                        ->orWhereHas('guide', function ($guideQuery) use ($query): void {
                            $guideQuery->where('full_name', 'like', '%'.$query.'%');
                        });
                });
            })
            ->when($selectedRegion !== '' && $selectedRegion !== 'all', fn ($builder) => $builder->where('region', $selectedRegion));

        if ($selectedDate !== '' && Schema::hasColumn('tours', 'available_on')) {
            $tourQuery->whereDate('available_on', '>=', $selectedDate);
        }

        if (Schema::hasColumn('tours', 'rating')) {
            $tourQuery->orderByDesc('rating');
        }

        $tourQuery->orderBy(Schema::hasColumn('tours', 'name') ? 'name' : 'id')
            ->limit(6);

        $tours = $tourQuery->get();
    }

    return view('welcome', [
        'tours' => $tours,
        'regions' => $regions,
        'filters' => [
            'q' => $query,
            'region' => $selectedRegion ?: 'all',
            'date' => $selectedDate,
        ],
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/dashboard/tourist', [DashboardController::class, 'tourist'])
        ->middleware('role:tourist')
        ->name('dashboard.tourist');
    Route::get('/dashboard/guide', [DashboardController::class, 'guide'])
        ->middleware('role:guide')
        ->name('dashboard.guide');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('dashboard.admin');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
