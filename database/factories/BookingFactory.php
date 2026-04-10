<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bookingRequest = BookingRequest::factory()->create();

        return [
            'booking_request_id' => $bookingRequest->id,
            'tourist_id' => $bookingRequest->tourist_id,
            'guide_id' => $bookingRequest->guide_id,
            'tour_id' => $bookingRequest->tour_id,
            'booking_date' => $bookingRequest->requested_date,
            'group_size' => $bookingRequest->group_size,
            'total_amount' => $bookingRequest->total_price,
            'commission_amount' => $bookingRequest->total_price * 0.1,
            'net_amount' => $bookingRequest->total_price * 0.9,
            'status' => 'completed',
            'pickup_location' => fake()->address(),
            'guest_names' => [fake()->name()],
            'special_notes' => fake()->paragraph(),
            'started_at' => now()->subDays(5),
            'completed_at' => now(),
        ];
    }
}
