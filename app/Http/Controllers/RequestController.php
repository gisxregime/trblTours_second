<?php

namespace App\Http\Controllers;

use App\Models\ServiceLocation;
use App\Models\TouristRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user?->role === 'tourist', 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string',
            'preferred_date' => 'required|date|after_or_equal:today',
            'passenger_count' => 'required|integer|min:1|max:20',
            'budget_min' => 'required|numeric|min:500',
            'budget_max' => 'required|numeric|min:budget_min|max:50000',
            'description' => 'required|string|max:1000',
        ]);

        $serviceLocation = ServiceLocation::firstOrCreate(['name' => $validated['location']]);

        TouristRequest::create([
            'tourist_id' => $user->id,
            'service_location_id' => $serviceLocation->id,
            'title' => $validated['title'],
            'preferred_date' => $validated['preferred_date'],
            'passenger_count' => $validated['passenger_count'],
            'budget_min' => $validated['budget_min'],
            'budget_max' => $validated['budget_max'],
            'description' => $validated['description'],
            'status' => 'open',
            'message' => $validated['description'], // Fallback
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request post created successfully! It will appear in the feed shortly.',
        ]);
    }
}
