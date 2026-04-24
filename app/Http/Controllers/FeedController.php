<?php

namespace App\Http\Controllers;

use App\Models\TouristRequest;
use App\Models\TourListing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->role === 'tourist', 403);

        $posts = $this->getFilteredPosts($request);

        return view('dashboards.tourist', [
            'posts' => $posts,
            'regions' => [
                'National Capital Region', 'Cordillera Administrative Region', 'Ilocos Region',
                'Cagayan Valley', 'Central Luzon', 'CALABARZON', 'MIMAROPA', 'Bicol Region',
                'Western Visayas', 'Central Visayas', 'Eastern Visayas', 'Zamboanga Peninsula',
                'Northern Mindanao', 'Davao Region', 'SOCCSKSARGEN', 'Caraga', 'BARMM',
            ],
            'location' => $request->query('location', ''),
            'postType' => $request->query('post_type', 'all'),
            'sortBy' => $request->query('sort_by', 'latest'),
        ]);
    }

    public function filter(Request $request): JsonResponse
    {
        $posts = $this->getFilteredPosts($request);

        return response()->json([
            'posts' => $posts->items(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'per_page' => $posts->perPage(),
            'total' => $posts->total(),
        ]);
    }

    private function getFilteredPosts(Request $request): LengthAwarePaginator
    {
        $location = $request->query('location', '');
        $postType = $request->query('post_type', 'all');
        $sortBy = $request->query('sort_by', 'latest');
        $perPage = 9; // 3x3 grid

        $tourListingsQuery = TourListing::query()
            ->when($location, fn ($q) => $q->where('location', 'like', '%'.$location.'%'));

        $touristRequestsQuery = TouristRequest::query()
            ->when($location, fn ($q) => $q->where('location', 'like', '%'.$location.'%'));

        if ($postType === 'tour_listings') {
            $postsQuery = $tourListingsQuery; // Remove withCount
        } elseif ($postType === 'request_posts') {
            $postsQuery = $touristRequestsQuery;
        } else {
            $tourListings = $tourListingsQuery->get()->map(fn ($p) => (array) $p + ['post_type' => 'tour_listing', 'comment_count' => 0]);
            $requests = $touristRequestsQuery->get()->map(fn ($p) => (array) $p + ['post_type' => 'request_post', 'comment_count' => 0]);
            $postsQuery = $tourListings->concat($requests);
        }

        // Apply sorting
        // Sort after query
        $page = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $allPostsQuery = match ($postType) {
            'tour_listings' => TourListing::query()->when($location, fn ($q) => $q->where('location', 'like', '%'.$location.'%')),
            'request_posts' => TouristRequest::query()->when($location, fn ($q) => $q->where('location', 'like', '%'.$location.'%')),
            default => TourListing::query()->union(TouristRequest::query())
        };
        $allPosts = $allPostsQuery->get();
        $allPosts = match ($sortBy) {
            'price_low_high' => $allPosts->sortBy(fn ($p) => $p->price ?? 0)->values(),
            'price_high_low' => $allPosts->sortByDesc(fn ($p) => $p->price ?? 0)->values(),
            default => $allPosts->sortByDesc('created_at')->values(),
        };
        $perPageItems = $allPosts->slice(($page - 1) * $perPage, $perPage);
        $posts = new LengthAwarePaginator($perPageItems, $allPosts->count(), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // Transform for view/JSON
        $posts->through(function ($post) {
            if ($post instanceof TourListing) {
                $post->post_type = 'tour_listing';
                $post->is_verified = $post->guide->tourGuideProfile?->id_verified ?? true;
                $post->guide_name = $post->guide->full_name ?? $post->guide->name;
                $post->location = $post->serviceLocation->name ?? '';
                $post->comment_count = $post->comments_count ?? 0;
            } elseif ($post instanceof TouristRequest) {
                $post->post_type = 'request_post';
                $post->tourist_name = $post->tourist->full_name ?? $post->tourist->name;
                $post->location = $post->serviceLocation->name ?? '';
                $post->selected_guide = $post->status === 'closed' ? User::find($post->selected_guide_id ?? null)?->full_name : null;
            }

            return $post;
        });

        return $posts;
    }
}
