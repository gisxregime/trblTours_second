<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TourListing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user?->role === 'tourist', 403);

        $validated = $request->validate([
            'tour_listing_id' => 'required|exists:tour_listings,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'passenger_count' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $tourListing = TourListing::findOrFail($validated['tour_listing_id']);

        // Simple booking creation (expand later w/ payment etc.)
        Booking::create([
            'tour_listing_id' => $tourListing->id,
            'tourist_id' => $user->id,
            'guide_id' => $tourListing->guide_id,
            'booking_date' => $validated['booking_date'],
            'passenger_count' => $validated['passenger_count'],
            'total_price' => $tourListing->price * $validated['passenger_count'],
            'special_requests' => $validated['special_requests'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking request sent to guide! You\'ll be notified soon.',
        ]);
    }
}
