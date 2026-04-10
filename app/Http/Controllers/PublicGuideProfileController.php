<?php

namespace App\Http\Controllers;

use App\Models\GuideStory;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicGuideProfileController extends Controller
{
    public function show(User $guide): View
    {
        // Verify the user is a guide
        if (! in_array($guide->role, ['guide', 'tour_guide'])) {
            abort(404, 'Guide not found');
        }

        // Load guide profile with optimized queries
        $posts = collect();
        if (Schema::hasTable((new GuideStory)->getTable())) {
            $guide->load([
                'guideStories' => function (HasMany $query) {
                    $query->orderByDesc('created_at');
                },
            ]);

            $posts = $guide->guideStories;
        }

        $guide->load([
            'marketplaceTours' => function (HasMany $query) {
                if (Schema::hasColumn((new Tour)->getTable(), 'status')) {
                    $query->where('status', 'active');
                }

                $query->orderByDesc('created_at');
            },
            'guideReviews' => function (HasMany $query) {
                $query->where('rating', '>', 0)
                    ->orderByDesc('created_at');
            },
        ]);

        // Calculate guide statistics
        $stats = [
            'totalToursCompleted' => $guide->bookingsAsGuide()
                ->where('status', 'completed')
                ->count(),
            'totalBookings' => $guide->bookingsAsGuide()
                ->where('status', 'completed')
                ->sum('total_guests'),
            'averageRating' => $guide->guideReviews()
                ->avg('rating') ?? 0,
            'totalReviews' => $guide->guideReviews()->count(),
        ];

        return view('public.guide.profile', [
            'guide' => $guide,
            'posts' => $posts,
            'tours' => $guide->marketplaceTours,
            'stats' => $stats,
        ]);
    }
}
