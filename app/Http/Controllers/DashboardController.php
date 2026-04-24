<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->dashboardRouteName());
    }

    public function tourist(Request $request): View
    {
        $user = $request->user();

        abort_unless($user->role === 'tourist', 403);

        $regions = [
            'National Capital Region',
            'Cordillera Administrative Region',
            'Ilocos Region',
            'Cagayan Valley',
            'Central Luzon',
            'CALABARZON',
            'MIMAROPA',
            'Bicol Region',
            'Western Visayas',
            'Central Visayas',
            'Eastern Visayas',
            'Zamboanga Peninsula',
            'Northern Mindanao',
            'Davao Region',
            'SOCCSKSARGEN',
            'Caraga',
            'BARMM',
        ];

        $location = trim((string) $request->query('location', ''));
        $postType = (string) $request->query('post_type', 'all');
        $sortBy = (string) $request->query('sort_by', 'latest');
        $perPage = 12;

        if (! in_array($postType, ['all', 'tour_listings', 'request_posts'], true)) {
            $postType = 'all';
        }

        if (! in_array($sortBy, ['latest', 'price_low_high', 'price_high_low'], true)) {
            $sortBy = 'latest';
        }

        $tourListings = collect();
        if (in_array($postType, ['all', 'tour_listings'], true)) {
            $userColumns = Schema::getColumnListing('users');

            $tourListings = Tour::query()
                ->with(['marketplaceGuide:id,name,full_name,role,status'])
                ->whereHas('marketplaceGuide', function (Builder $query) use ($userColumns): void {
                    $query->whereIn('role', ['guide', 'tour_guide'])
                        ->where('status', 'active');

                    $query->where(function (Builder $verificationQuery) use ($userColumns): void {
                        $hasAnyVerificationColumn = false;

                        if (in_array('verification_status', $userColumns, true)) {
                            $verificationQuery->orWhere('verification_status', 'verified');
                            $hasAnyVerificationColumn = true;
                        }

                        if (in_array('is_verified', $userColumns, true)) {
                            $verificationQuery->orWhere('is_verified', true);
                            $hasAnyVerificationColumn = true;
                        }

                        if (in_array('approved_by_admin', $userColumns, true)) {
                            $verificationQuery->orWhere('approved_by_admin', true);
                            $hasAnyVerificationColumn = true;
                        }

                        if (! $hasAnyVerificationColumn) {
                            $verificationQuery->orWhereRaw('1 = 1');
                        }
                    });
                })
                ->when($location !== '', function ($query) use ($location): void {
                    $query->where('region', $location);
                })
                ->latest()
                ->get()
                ->map(function (Tour $tour): array {
                    return [
                        'type' => 'tour_listing',
                        'id' => (int) $tour->id,
                        'created_at' => $tour->created_at,
                        'price_value' => (float) ($tour->price_per_person ?? $tour->price ?? 0),
                        'data' => $tour,
                    ];
                });
        }

        $requestPosts = collect();
        if (in_array($postType, ['all', 'request_posts'], true)) {
            $requestPosts = BookingRequest::query()
                ->select('booking_requests.*')
                ->addSelect([
                    'comment_count' => DB::table('messages')
                        ->join('conversations', 'conversations.id', '=', 'messages.conversation_id')
                        ->selectRaw('count(messages.id)')
                        ->whereColumn('conversations.tourist_id', 'booking_requests.tourist_id')
                        ->whereColumn('conversations.guide_id', 'booking_requests.guide_id')
                        ->whereColumn('conversations.tour_id', 'booking_requests.tour_id'),
                ])
                ->with([
                    'tourist:id,name,full_name',
                    'tour:id,title,region,duration_label',
                ])
                ->when($location !== '', function ($query) use ($location): void {
                    $query->whereHas('tour', function ($tourQuery) use ($location): void {
                        $tourQuery->where('region', $location);
                    });
                })
                ->latest()
                ->get()
                ->map(function (BookingRequest $bookingRequest): array {
                    return [
                        'type' => 'request_post',
                        'id' => (int) $bookingRequest->id,
                        'created_at' => $bookingRequest->created_at,
                        'price_value' => (float) $bookingRequest->total_price,
                        'data' => $bookingRequest,
                    ];
                });
        }

        $posts = $tourListings->concat($requestPosts);

        $posts = match ($sortBy) {
            'price_low_high' => $posts->sortBy('price_value')->values(),
            'price_high_low' => $posts->sortByDesc('price_value')->values(),
            default => $posts->sortByDesc('created_at')->values(),
        };

        $page = LengthAwarePaginator::resolveCurrentPage();
        $total = $posts->count();
        $results = $posts->slice(($page - 1) * $perPage, $perPage)->values();

        $paginatedPosts = new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('dashboards.tourist', [
            'regions' => $regions,
            'location' => $location,
            'postType' => $postType,
            'sortBy' => $sortBy,
            'posts' => $paginatedPosts,
        ]);
    }

    public function guide(Request $request): View
    {
        $user = $request->user();

        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        return view('dashboards.guide-unavailable');
    }

    // Scroll nav CSS in blade

    public function admin(): View
    {
        abort_unless(request()->user()?->role === 'admin', 403);

        $totalUsers = User::query()->count();
        $activeGuides = User::query()
            ->whereIn('role', ['guide', 'tour_guide'])
            ->where('status', 'active')
            ->count();

        $featuredTours = Schema::hasTable('tours') && Schema::hasColumn('tours', 'is_featured')
            ? Tour::query()->where('is_featured', true)->count()
            : 0;

        $pendingGuideApprovals = Schema::hasTable('tour_guides_profile') && Schema::hasColumn('tour_guides_profile', 'approved_by_admin')
            ? DB::table('tour_guides_profile')->where('approved_by_admin', false)->count()
            : 0;

        $recentUsers = User::query()
            ->select(['name', 'email', 'role', 'status', 'created_at'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboards.admin', [
            'stats' => [
                'total_users' => $totalUsers,
                'active_guides' => $activeGuides,
                'pending_guide_approvals' => $pendingGuideApprovals,
                'featured_tours' => $featuredTours,
            ],
            'recentUsers' => $recentUsers,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getGuideProfile(int $userId): array
    {
        if (! Schema::hasTable('tour_guides_profile')) {
            return [];
        }

        return (array) (DB::table('tour_guides_profile')->where('user_id', $userId)->first() ?? []);
    }

    private function firstName(string $name): string
    {
        $trimmed = trim($name);

        if ($trimmed === '') {
            return 'Guide';
        }

        return explode(' ', $trimmed)[0];
    }

    /**
     * @param  array<string, mixed>  $record
     * @param  array<int, string>  $keys
     */
    private function isFilled(array $record, array $keys): bool
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $record)) {
                continue;
            }

            $value = $record[$key];

            if (is_string($value) && trim($value) !== '') {
                return true;
            }

            if (is_numeric($value) && (string) $value !== '') {
                return true;
            }
        }

        return false;
    }

    private function hasAtLeastOneSelection(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_array($value)) {
            return count(array_filter($value, fn (mixed $item): bool => filled($item))) >= 1;
        }

        if (! is_string($value)) {
            return false;
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return count(array_filter($decoded, fn (mixed $item): bool => filled($item))) >= 1;
        }

        return collect(explode(',', $value))
            ->map(fn (string $item): string => trim($item))
            ->filter(fn (string $item): bool => $item !== '')
            ->isNotEmpty();
    }

    /**
     * @param  array<string, mixed>  $profile
     */
    private function hasPendingVerification(array $profile): bool
    {
        $pendingPairs = [
            ['path' => 'id_front_path', 'verified' => 'id_front_verified'],
            ['path' => 'id_back_path', 'verified' => 'id_back_verified'],
            ['path' => 'selfie_path', 'verified' => 'selfie_verified'],
            ['path' => 'nbi_clearance_path', 'verified' => 'nbi_clearance_validated'],
        ];

        foreach ($pendingPairs as $pair) {
            $pathValue = $profile[$pair['path']] ?? null;
            $verificationValue = $profile[$pair['verified']] ?? null;

            if (is_string($pathValue) && trim($pathValue) !== '' && ! (bool) $verificationValue) {
                return true;
            }
        }

        if (array_key_exists('approved_by_admin', $profile) && ! (bool) $profile['approved_by_admin']) {
            return $this->isFilled($profile, ['id_front_path', 'id_back_path', 'selfie_path', 'nbi_clearance_path', 'barangay_clearance_path']);
        }

        return false;
    }
}
