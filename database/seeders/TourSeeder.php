<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = User::query()
            ->whereIn('role', ['guide', 'tour_guide'])
            ->orderBy('id')
            ->get();

        if ($guides->isEmpty()) {
            $guides = User::factory()
                ->count(6)
                ->guide()
                ->state(['role' => 'tour_guide'])
                ->create();
        }

        $featuredTours = [
            [
                'title' => 'El Nido Lagoon Explorer',
                'region' => 'Mimaropa',
                'summary' => 'Boat-based island hopping through limestone lagoons, hidden coves, and swim stops.',
                'duration_label' => 'Full-day',
                'price_per_person' => 3800,
                'rating' => 4.9,
                'image_path' => 'hero/elnido.jpg',
            ],
            [
                'title' => 'Palawan Coastal Discovery',
                'region' => 'Mimaropa',
                'summary' => 'A coastline-focused route with snorkeling sites, beach lunch, and sunset viewpoints.',
                'duration_label' => 'Full-day',
                'price_per_person' => 3400,
                'rating' => 4.8,
                'image_path' => 'hero/palawan.jpg',
            ],
            [
                'title' => 'Batad Rice Terraces Trek',
                'region' => 'Cordillera Administrative Region',
                'summary' => 'Guided highland trek across iconic terraces with cultural storytelling and photo stops.',
                'duration_label' => '2 days 1 night',
                'price_per_person' => 4200,
                'rating' => 4.7,
                'image_path' => 'hero/batad.jpg',
            ],
            [
                'title' => 'Davao Nature Escape',
                'region' => 'Davao Region',
                'summary' => 'Local forest and river route with nature interpretation and community food stops.',
                'duration_label' => 'Half-day',
                'price_per_person' => 1800,
                'rating' => 4.6,
                'image_path' => 'hero/davao.jpg',
            ],
            [
                'title' => 'Pangasinan Islands Viewpoint Tour',
                'region' => 'Ilocos Region',
                'summary' => 'Scenic coastal viewpoints and island panoramas with flexible stops for photography.',
                'duration_label' => 'Full-day',
                'price_per_person' => 2900,
                'rating' => 4.8,
                'image_path' => 'hero/pangasinan.jpg',
            ],
            [
                'title' => 'Puerto Princesa Reef and Coastline',
                'region' => 'Mimaropa',
                'summary' => 'Reef-edge swim spots and calm coves with route planning based on daily sea conditions.',
                'duration_label' => 'Full-day',
                'price_per_person' => 3600,
                'rating' => 4.9,
                'image_path' => 'hero/puertoprincessa.jpg',
            ],
        ];

        foreach ($featuredTours as $index => $tourData) {
            $guide = $guides[$index % $guides->count()];

            Tour::query()->updateOrCreate(
                ['title' => $tourData['title']],
                [
                    ...$tourData,
                    'guide_id' => $guide->id,
                    'is_featured' => true,
                    'available_on' => now()->addDays(($index + 1) * 3)->toDateString(),
                ]
            );
        }
    }
}
