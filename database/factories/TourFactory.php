<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regions = [
            'National Capital Region',
            'Central Visayas',
            'Davao Region',
            'Western Visayas',
            'Ilocos Region',
            'Bicol Region',
        ];

        $titles = [
            'Sunrise Island Hopping',
            'Heritage Streets & Local Eats',
            'Coastal Snorkel Escape',
            'Waterfall and Highland Trek',
            'Culture and Craft Village Walk',
            'Hidden Beach Discovery Tour',
        ];

        return [
            'guide_id' => User::factory()->guide(),
            'title' => fake()->randomElement($titles),
            'region' => fake()->randomElement($regions),
            'summary' => fake()->sentence(18),
            'duration_label' => fake()->randomElement(['Half-day', 'Full-day', '2 days 1 night']),
            'price_per_person' => fake()->randomFloat(2, 1200, 8500),
            'rating' => fake()->randomFloat(1, 4.3, 5.0),
            'image_path' => fake()->randomElement([
                'hero/elnido.jpg',
                'hero/palawan.jpg',
                'hero/batad.jpg',
                'hero/davao.jpg',
                'hero/pangasinan.jpg',
                'hero/puertoprincessa.jpg',
            ]),
            'is_featured' => true,
            'available_on' => fake()->dateTimeBetween('now', '+90 days')->format('Y-m-d'),
        ];
    }

    public function notFeatured(): static
    {
        return $this->state(fn () => ['is_featured' => false]);
    }
}
