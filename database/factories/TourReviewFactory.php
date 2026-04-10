<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\TourReview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TourReview>
 */
class TourReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $booking = Booking::factory()->create();

        return [
            'booking_id' => $booking->id,
            'tourist_id' => $booking->tourist_id,
            'guide_id' => $booking->guide_id,
            'tour_id' => $booking->tour_id,
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->paragraph(),
            'is_featured' => false,
        ];
    }
}
