<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingRequest>
 */
class BookingRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tourist = User::factory()->create(['role' => 'tourist']);
        $guide = User::factory()->create(['role' => 'guide']);
        $tour = Tour::factory()->create(['guide_id' => $guide->id]);
        $totalPrice = fake()->randomFloat(2, 1000, 10000);

        return [
            'tourist_id' => $tourist->id,
            'guide_id' => $guide->id,
            'tour_id' => $tour->id,
            'requested_date' => now()->addDays(rand(1, 30)),
            'group_size' => rand(1, 10),
            'total_price' => $totalPrice,
            'special_requests' => fake()->paragraph(),
            'status' => 'approved',
            'decline_reason' => null,
        ];
    }
}
